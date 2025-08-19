<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
error_reporting(0);

class ApiHandler
{
    private $prev;
    private $response;

    public function __construct()
    {
        $this->response = ['status' => 'error'];
        $this->prev = $this->sanitize_prev($_GET['prev'] ?? null);
    }

    public function handle(): void
    {
        if ($this->prev === null)
        {
            $this->respond_and_exit(['status' => 'error', 'message' => 'Missing or invalid prev parameter']);
        }

        for ($i = 1; $i <= 25; $i++)
        {
            $live_data = $this->get_live_data("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/api/live-run");

            if ($live_data['status'] === 'success')
            {
                $data = $live_data['data'][0] ?? null;
                if (!$this->validate_data($data))
                {
                    $this->respond_and_exit(['status' => 'error', 'message' => 'Invalid data structure from live API']);
                }

                $this->response['status'] = 'success';

                $file_individual = $this->get_individual_progress_path($data);

                if (file_exists($file_individual))
                {
                    $file_mtime = @filemtime($file_individual);
                    if ($file_mtime === false)
                    {
                        $this->respond_and_exit(['status' => 'error', 'message' => 'Could not read file modification time']);
                    }

                    $data['filemtime'] = $file_mtime;
                    $data['new_ind'] = '0';
                    $data['new_team'] = '0';
                    $data['participation_type'] = $data['participation_type'] ?? '';

                    if ($file_mtime !== $this->prev)
                    {
                        $ind_progress = @file_get_contents($file_individual);
                        if ($ind_progress === false)
                        {
                            $this->respond_and_exit(['status' => 'error', 'message' => 'Could not read individual progress file']);
                        }
                        $this->response['ind_progress'] = $ind_progress;
                        $data['new_ind'] = '1';

                        if (
                            isset($data['participation_type']) &&
                            $data['participation_type'] === 't'
                        )
                        {
                            $file_team = $this->get_team_progress_path($data);
                            if (file_exists($file_team))
                            {
                                $team_progress = @file_get_contents($file_team);
                                if ($team_progress === false)
                                {
                                    $this->respond_and_exit(['status' => 'error', 'message' => 'Could not read team progress file']);
                                }
                                $this->response['team_progress'] = $team_progress;
                                $data['new_team'] = '1';
                            }
                        }
                    }

                    $live_json = json_encode($data);
                    if ($live_json === false)
                    {
                        $this->respond_and_exit(['status' => 'error', 'message' => 'Failed to encode live_json']);
                    }
                    $this->response['live_json'] = $live_json;
                }
                else
                {
                    $this->respond_and_exit(['status' => 'error', 'message' => 'Progress file not found']);
                }

                break;
            }
        }

        $this->respond_and_exit($this->response);
    }

    private function get_live_data(string $url): array
    {
        $result = ['status' => 'error', 'data' => null];

        $data = @file_get_contents($url);
        if ($data === false)
        {
            $result['message'] = 'Failed to fetch data from live API url: ' . $url;
            return $result;
        }

        $decoded_data = json_decode($data, true);
        if (is_array($decoded_data) && isset($decoded_data[0]['trial_id']))
        {
            $result['status'] = 'success';
            $result['data'] = $decoded_data;
        }
        else
        {
            $result['message'] = 'Invalid data structure from live API';
        }

        return $result;
    }

    private function get_individual_progress_path(array $data): string
    {
        $base_path = $_SERVER['DOCUMENT_ROOT'] .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . "sat" .
            DIRECTORY_SEPARATOR . "l52" .
            DIRECTORY_SEPARATOR . "storage" .
            DIRECTORY_SEPARATOR . "trialcache" .
            DIRECTORY_SEPARATOR;

        $trial_path = $data['trial_id'] . DIRECTORY_SEPARATOR;
        $file_name = $data['trial_id'] . "-" . $data['round_id'] . "-progress.txt";

        return $base_path . $trial_path . $file_name;
    }

    private function get_team_progress_path(array $data): string
    {
        $base_path = $_SERVER['DOCUMENT_ROOT'] .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . "sat" .
            DIRECTORY_SEPARATOR . "l52" .
            DIRECTORY_SEPARATOR . "storage" .
            DIRECTORY_SEPARATOR . "trialcache" .
            DIRECTORY_SEPARATOR;

        $trial_path = $data['trial_id'] . DIRECTORY_SEPARATOR;
        $file_name = $data['trial_id'] . "+" . $data['round_id'] . "-progress.txt";

        return $base_path . $trial_path . $file_name;
    }

    private function validate_data($data): bool
    {
        return is_array($data)
            && isset($data['trial_id'])
            && isset($data['round_id']);
    }

    private function sanitize_prev($prev): ?int
    {
        if ($prev === null)
        {
            return null;
        }
        if (!is_numeric($prev))
        {
            return null;
        }
        return (int)$prev;
    }

    private function respond_and_exit(array $response): void
    {
        $json = json_encode($response);
        if ($json === false)
        {
            echo '{"status":"error","message":"JSON encoding failed"}';
        }
        else
        {
            echo $json;
        }
        exit;
    }
}

$api = new ApiHandler();
$api->handle();
