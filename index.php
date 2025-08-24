<!DOCTYPE html>
<html style="width: 1080px; overflow: hidden;">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Polish Open - Results</title>
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/vue.min.js"></script>
    <script src="js/flags.js"></script>
</head>

<body>

    <div id="app" class="container">
        <div class="left-col">
            <div class="table_container">
                <div class="table_header">
                    <div class="title">STARTLIST</div>
                    <div class="flex-right">
                        <img src="img/powered.png">
                    </div>
                </div>
                <table>
                    <tr>
                        <th width="3%">#</th>
                        <th width="4%">D</th>
                        <th width="3%">C</th>
                        <th width="30%">Handler</th>

                        <th v-if="!is_team" width="30%">Dog</th>
                        <th v-else width="10%">Dog</th>

                        <th v-if="!is_team" width="20%">Breed</th>
                        <th v-else width="20%">Breed</th>

                        <th v-if="is_team" width="20%">Team</th>
                    </tr>
                    <tr v-for="run in trimmedStartlist" :key="run.index">
                        <td class="text-center">
                            {{ run.index }}
                        </td>
                        <td class="text-center">
                            {{ run.dorsal }}
                        </td>
                        <td class="text-center">
                            <img class="flags" :src="'img/flags/' + getFlag(run.country_name)" height="12" width="18">
                        </td>
                        <td>
                            <span :style="{fontSize: scaleFont(run.handler,32)+'px'}">{{ formatHandler(run.handler) }}</span>
                        </td>
                        <td>
                            <span :style="{fontSize: scaleFont(run.dog_short,14)+'px', textAlign:'center'}">{{ run.dog_short }}</span>
                        </td>
                        <td>
                            <span :style="{fontSize: scaleFont(run.breed,17)+'px'}">{{ run.breed }}</span>
                        </td>
                        <td v-if="is_team">
                            <span :style="{fontSize: scaleFont(run.team_name||'',18)+'px', textAlign:'center'}">
                                {{ run.team_name }}
                            </span>
                        </td>
                    </tr>
                </table>
                <div v-if="isStartlistTrimmed" class="table_footer">
                    and {{ invisibleStartlistCount }} more...
                </div>
            </div>

            <div class="table_container">
                <div class="table_header">
                    <div class="title">RESULTS</div>
                </div>
                <table>
                    <tr>
                        <th width="4%">#</th>
                        <th width="5%">D</th>
                        <th width="4%">C</th>
                        <th width="29%">Handler</th>
                        <th width="20%">Dog</th>
                        <th width="24%">Breed</th>
                        <th width="7%">Faults</th>
                        <th width="7%">Time</th>
                    </tr>
                    <tr v-for="ranked_run in trimmedResults" :key="ranked_run.ranking + '-' + ranked_run.dorsal">
                        <td class="text-center">{{ ranked_run.ranking }}</td>
                        <td class="text-center">{{ ranked_run.dorsal }}</td>
                        <td class="text-center">
                            <img class="flags" :src="'img/flags/' + getFlag(ranked_run.country_name)" height="12" width="18">
                        </td>
                        <td>
                            <span :style="{fontSize: scaleFont(ranked_run.handler,24)+'px'}">{{ formatHandler(ranked_run.handler) }}</span>
                        </td>
                        <td>
                            <span :style="{fontSize: scaleFont(ranked_run.dog_short,12)+'px', textAlign:'center'}">{{ ranked_run.dog_short }}</div>
                        </td>
                        <td>
                            <span :style="{fontSize: scaleFont(ranked_run.breed,17)+'px'}">{{ ranked_run.breed }}</span>
                        </td>
                        <td class="text-center">{{ ranked_run.total_faults }}</td>
                        <td class="text-center">{{ ranked_run.course_time }}</td>
                    </tr>
                </table>
<div v-if="isResultsTrimmed" class="table_footer">
    and {{ invisibleResultsCount }} more...
</div>
</div>
        </div>
        <div class="right-col">
            <div class="right-top">
                <img class="logo" src="./img/cooldog.png">
            </div>
            <div class="right-bottom">

                <div class="card">

                    <div class="card-header">
                        <span class="title">RING {{ ring_info.ring }}</span>
                    </div>

                    <div class="card-content">
                        <div class="info-list">
                            <div v-if="ring_info.category" class="info-row">
                                <span class="info-label">Type:</span>
                                <span class="info-value">{{ ring_info.type }} {{ ring_info.category.toUpperCase() }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Judge:</span>
                                <span class="info-value">{{ ring_info.judge }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Length:</span>
                                <span class="info-value">{{ ring_info.length }}m</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">SCT:</span>
                                <span class="info-value">{{ ring_info.current_sct }}s</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">MCT:</span>
                                <span class="info-value">{{ ring_info.current_mct }}s</span>
                            </div>
                            <div class="info-row progress-row">
                                <div class="progress-bar-container">
                                    <div class="progress-bar" :style="{'width': progress_info.percent + '%'}"></div>
                                    <span class="progress-bar-label">{{ progress_info.runs_completed }} / {{ progress_info.runs_total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">

                    <div class="card-header">
                        <span class="title">Now in Ring</span>
                        <span class="title-right">{{ live_status.dorsal }}</span>
                    </div>

                    <div class="card-content">
                        
                        <div v-if="live_status.team_name" class="info-team" :style="{fontSize: scaleFont(live_status.team_name, 20, 24)+'px'}">
                            {{ live_status.team_name }}
                        </div>    
                        <div v-if="live_status.team_name" class="info-separator"></div>
                        <div class="info-handler" :style="{fontSize: scaleFont(live_status.handler, 20, 24)+'px'}">
                            {{ live_status.handler }}
                        </div>
                        <div class="info-separator"></div>
                        <div class="info-dog" :style="{fontSize: scaleFont(live_status.dog_call_name, 20, 24)+'px'}">{{ live_status.dog_call_name }}</div>

                        <div v-if="live_status.is_eliminated" class="info-dis">
                                ELIMINATED
                        </div>

                        <div v-else class="info-list">
                            <div class="info-row faults-row">
                                <div class="info-faults">
                                    {{ live_status.faults }}
                                    <div class="info-faults-label">FAULTS</div>
                                </div>
                                <div class="info-refusals">
                                    {{ live_status.refusals }}
                                    <div class="info-refusals-label">REFUSALS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">

                    <div class="card-header">
                        <span class="card-title">Latest Runs</span>
                    </div>

                    <div class="card-content">
                        <div class="latest_run" v-for="recent_run in latest_runs" :key="recent_run.dorsal + '-' + recent_run.handler">
                            <div class="latest_run_ranking">{{ recent_run.total_faults == 'Elim.' ? '🙅' : recent_run.ranking }}</div>

                            <div class="latest_run_content">
                                <div class="latest_run_handler" :style="{fontSize: scaleFont(formatHandler(recent_run.handler) + ' - ' + recent_run.dog_short, 26, 16)+'px'}">
                                    {{ formatHandler(recent_run.handler) }} - {{ recent_run.dog_short }}
                                </div>
                                <div class="latest_run_results" v-if="recent_run.total_faults == 'Elim.'">--- ELIM ---</div>
                                <div class="latest_run_results" v-else>{{ recent_run.dorsal }} | F: {{ recent_run.total_faults }} | T: {{ recent_run.course_time }}s</div>
                            </div>
                        </div>
                    </div>
                </div>


                <div v-if="is_team" class="card">

                    <div class="card-header">
                        <span class="card-title">Team Results</span>
                    </div>

                    <div class="card-content">
                        <div class="latest_run" v-for="run in ranked_team_runs" :key="run.team_name">
                            
                            <div class="latest_run_ranking" v-if="run.total_team_time == 0">🙅</div>
                            <div v-else>
                                <div class="latest_run_ranking" v-if="run.ranking == 'Incomplete'">🏃</div>
                                <div class="latest_run_ranking" v-else>{{ run.ranking }}</div>
                            </div>
                            
                            <div class="latest_run_content">
                                <div class="latest_run_handler">{{ run.team_name }}</div>
                                
                                <div class="latest_run_results" v-if="run.total_faults == 'Elim.'">--- ELIM ---</div>
                                <div class="latest_run_results" v-else>F: {{ run.total_team_faults }} | T: {{ run.total_team_time }}s</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <script>
    new Vue({
        el: '#app',
        data: {

            // Minimum number of startlist entries to show, you can change this value
            // to adjust how many entries are visible in the startlist section.
            STARTLIST_MIN_VISIBLE: 40,

            // Maximum number of entries to show in total (startlist + results)
            // Please do not change this value, it is very dependent on the layout and font sizes
            MAX_VISIBLE_ENTRIES: 84,

            // Number of entries to reduce in the footer when there are too many results
            OVERFLOW_ENTRIES_REDUCTION: 1,


            ring_info: {},
            runs: [],
            ranked_runs: [],
            progress_info: {},
            latest_runs: [],
            ranked_team_runs: [],
            live_status: {},
            flag_map: window.Flag || {},
            is_team: false,
            results_count: 0,
            startlist_count: 0,
            show_now_in_ring: false
        },
computed: {
            isTeam() {
                return this.live_status.participation_type === 't';
            },
            
            // Helper method to calculate trimming limits
            calculatedLimits() {
                const minStartlist = this.STARTLIST_MIN_VISIBLE;
                const maxEntries = this.MAX_VISIBLE_ENTRIES;
                const totalStartlist = this.runs.length;

                const firstElimIndex = this.ranked_runs.findIndex(run => run.total_faults === "Elim.");
                const effectiveResultsLength = firstElimIndex !== -1 ? firstElimIndex + 1 : this.ranked_runs.length;
                let startlistToShow = Math.min(totalStartlist, minStartlist);
                const remainingSpace = maxEntries - startlistToShow;
                const resultsTrimmed = effectiveResultsLength > remainingSpace;
                const spaceForResultsFooter = resultsTrimmed ? 1 : 0;
                let maxResults = Math.min(effectiveResultsLength, remainingSpace - spaceForResultsFooter);
                
                const usedSpace = startlistToShow + maxResults + spaceForResultsFooter;
                const extraSpace = maxEntries - usedSpace;
                if (extraSpace > 0 && totalStartlist > startlistToShow) {
                    const extraStartlist = Math.min(extraSpace, totalStartlist - startlistToShow);
                    startlistToShow += extraStartlist;
                }
                
                const startlistTrimmed = totalStartlist > startlistToShow;
                const spaceForStartlistFooter = startlistTrimmed ? 1 : 0;
                
                if (resultsTrimmed && startlistTrimmed) {
                    if (startlistToShow + spaceForStartlistFooter + maxResults + spaceForResultsFooter > maxEntries) {
                        startlistToShow = Math.min(totalStartlist, minStartlist);
                        maxResults = Math.min(effectiveResultsLength, maxEntries - startlistToShow - spaceForStartlistFooter - spaceForResultsFooter);
                    }
                }

                return {
                    startlistToShow,
                    maxResults,
                    resultsTrimmed,
                    startlistTrimmed: totalStartlist > startlistToShow,
                    effectiveResultsLength
                };
            },
            
            trimmedResults() {
                return this.ranked_runs.slice(0, this.calculatedLimits.maxResults);
            },
            
            isResultsTrimmed() {
                return this.calculatedLimits.resultsTrimmed;
            },
            
            invisibleResultsCount() {
                return Math.max(0, this.ranked_runs.length - this.calculatedLimits.maxResults);
            },
            
            trimmedStartlist() {
                return this.runs.slice(0, this.calculatedLimits.startlistToShow);
            },
            
            isStartlistTrimmed() {
                return this.calculatedLimits.startlistTrimmed;
            },
            
            invisibleStartlistCount() {
                return Math.max(0, this.runs.length - this.calculatedLimits.startlistToShow);
            }
        },
        methods: {
            fetchLiveJson() {
                fetch("api.php?prev=0")
                    .then(r => r.json())
                    .then(data =>
                    {
                        if (!data.status || data.status === "error")
                        {
                            setTimeout(this.fetchLiveJson, 1000);
                            return;
                        }
                        
                        try
                        {
                            const live_data = JSON.parse(data.live_json) || {};

                            this.live_status = {
                                dorsal: live_data.dorsal,
                                handler: live_data.handler,
                                dog_call_name: live_data.dog_call_name,
                                team: live_data.team,
                                is_eliminated: live_data.is_eliminated,
                                faults: live_data.faults,
                                refusals: live_data.refusals,
                                course_time: live_data.course_time,
                                touch_faults: live_data.touch_faults,
                                team_name: live_data.team
                            };

                            this.is_team = live_data.participation_type === "t";

                            if (live_data.new_ind == "1")
                            {
                                let individual_progress = JSON.parse(data.ind_progress);
                                this.ring_info = individual_progress.round || {};
                                
                                this.runs = individual_progress.runs;
                                this.runs.forEach(run => {
                                    if (run.country_name === "") {
                                        run.country_name = "undefined";
                                    }
                                });

                                this.ranked_runs = individual_progress.rankedRuns;
                                this.latest_runs = individual_progress.latestRuns;
                                
                                this.progress_info = individual_progress.progress || {};
                                this.progress_info.runs_left = this.progress_info.runs_total;
                                this.progress_info.runs_total += this.progress_info.runs_completed;
                                this.progress_info.percent = (this.progress_info.runs_completed / this.progress_info.runs_total) * 100.0;

                                this.startlist_count = this.runs.length;
                                this.results_count = this.ranked_runs.length;
                                
                                console.log(this.latest_runs);
                            }

                            if (live_data.new_team == "1" && this.is_team)
                            {
                                let team_progress = JSON.parse(data.team_progress);
                                this.ranked_team_runs = (team_progress.rankedRuns || []).map(team => {
                                    // Calculate total_team_time for each team for display
                                    let total_team_time = 0;
                                    let total_team_faults = 0;
                                    let top3 = 0;
                                    (team.details || []).forEach(detail => {
                                        top3++;
                                        if (detail.faults != "Elim.")
                                        {
                                            total_team_time += parseFloat(detail.time);
                                            total_team_faults += parseFloat(detail.faults);
                                        }
                                        if (top3 == 3) return false;
                                    });

                                    total_team_time = total_team_time.toFixed(2);
                                    total_team_faults = total_team_faults.toFixed(2);

                                    return {
                                        ...team,
                                        total_team_time,
                                        total_team_faults
                                    };
                                });
                            }

                            console.log(this.ranked_team_runs);

                            this.show_now_in_ring = !!(
                                this.latest_runs.length ||
                                live_data.course_time ||
                                live_data.refusals ||
                                live_data.faults ||
                                live_data.is_eliminated ||
                                live_data.touch_faults
                            );

                        } catch (e) {}
                        setTimeout(this.fetchLiveJson, 1000);
                    })
                    .catch(() => setTimeout(this.fetchLiveJson, 1000));
            },

            formatHandler(handler_name) {
                if (!handler_name) return "";
                let arr = handler_name.split(",");
                return arr.length > 1 ? (arr[1] + " " + arr[0]).trim() : handler_name;
            },

            scaleFont(str, max, base=24) {
                let pxh = base;
                if (!str) return pxh;
                if (str.length > max) pxh = pxh * max / str.length;
                if (pxh < 10) pxh = 10;
                return pxh;
            },

            getFlag(country_name) {
                return this.flag_map[country_name.replace(" ","")] + ".svg"; 
            }
        },
        mounted() {
            this.fetchLiveJson();
        }
    });
    </script>
</body>

</html>