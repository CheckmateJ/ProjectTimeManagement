<template>
  <div class="container">
    <div class="d-inline-flex  justify-content-center timer-box">
      <form name="project_time" method="post" class="d-inline-flex">
        <div class="me-3"><input v-on:keyup.enter="startTime" id="project_name" name="project[name]" required="required"
                                 class="input-group-text form-control project-name-input "
                                 placeholder="project name"></div>
        <div class="time-hours" ref="hours">00:</div>
        <div class="time-minutes" ref="minutes">00:</div>
        <div class="time-seconds me-2" ref="seconds">00</div>
        <div class="d-inline-flex mb-2 btn-functions">
          <input style="display: none" name="project[projectReports][1][timeOfProject]" ref="time-of-project"
                 required="required">
          <div>
            <a class="btn  me-2 btn-start" @click="startTime" ref="start-button">Start</a>
          </div>
          <div>
            <a class="btn btn-dark me-2" type="submit" ref="pause-button" @click="pauseTime">Pause</a>
          </div>
          <div>
            <button class="btn btn-dark me-2" name="project_time[save]" ref="stop-button" @click="stopTime"
                    type="submit">Stop
            </button>
          </div>
          <div>
            <a class="btn btn-dark me-2" @click="resetTime" ref="reset-button">Reset</a>
          </div>
        </div>
      </form>
    </div>
    <div class="list-group" ref="list-group" v-for="(projects,key) in projectsData">
      <div class="project-display" v-for="(date,project) in projects">
        <p class="date-display">{{ date }}{{ key }}</p>
        <div class="d-inline-flex project-content-box">
          <div class="list-group-item list-group-item-action">
            {{ project }}
            <a>{{ counts[project + ' ' + date] }}</a>
            <button class="toggle-button" ref="toggle-button" @click="showChildProjects(project,date)">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="26" fill="currentColor"
                   class="bi bi-list-nested" viewBox="0 0 16 20">
                <path fill-rule="evenodd"
                      d="M4.5 11.5A.5.5 0 0 1 5 11h10a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm-2-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm-2-4A.5.5 0 0 1 1 3h10a.5.5 0 0 1 0 1H1a.5.5 0 0 1-.5-.5z"/>
              </svg>
            </button>
            <div class="time-display">
              <p>{{ projectHours[key] }}:{{ projectMinutes[key] }}:{{ projectSeconds[key] }}</p>
            </div>
            <form method="post">
              <button class="btn btn-danger btn-sm delete-button">Delete</button>
            </form>
          </div>
        </div>
        <div v-bind:class="'list-group-item list-group-item-action child-data-project-' + project"
             ref="child-data-project">
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  props: ['projectsTime'],
  data() {
    return {
      test: 'test',
      hours: '00',
      minutes: '00',
      seconds: '0',
      timer: null,
      time: '',
      projectsData: [],
      projectDate: '',
      todayDate: '',
      counts: {},
      projectSeconds: [],
      projectMinutes: [],
      projectHours: [],
    }
  },
  mounted() {
    this.$refs["pause-button"].style.display = 'none';
    this.$refs["stop-button"].style.display = 'none';
    this.$refs["reset-button"].style.display = 'none';

  },
  beforeMount() {
    this.counts = {};
    let j = -1;

    this.projectsTime.forEach(project => {
      let lastCharInDate = project.createdAt.indexOf('T');
      let projectDate = project.createdAt.slice(0, lastCharInDate);
      this.counts[project.projectName.name + ' ' + projectDate] = (this.counts[project.projectName.name + ' ' + projectDate] || 0) + 1;
    });
    for (let i = this.projectsTime.length - 1; i >= 0; i--) {
      let lastCharInDate = this.projectsTime[i].createdAt.indexOf('T');
      let projectDate = this.projectsTime[i].createdAt.slice(0, lastCharInDate);
      this.todayDate = new Date().getUTCFullYear() + '-' + (new Date().getUTCMonth() + 1 < 10 ? '0' + (new Date().getUTCMonth() + 1) : new Date().getUTCMonth() + 1) + '-' + new Date().getUTCDate();

      if (typeof this.projectsData[j] == 'undefined' || this.projectsData[j][this.projectsTime[i].projectName.name] !== projectDate || !this.projectsData[j][this.projectsTime[i].projectName.name]) {
        let name = this.projectsTime[i].projectName.name
        this.projectsData.push({[name]: projectDate})
        j++

        let projectsTime = this.projectsTime;
        let seconds = 0;
        let minutes = 0;
        let hours = 0;
        let projects = projectsTime.filter(name => name.projectName.name === projectsTime[i].projectName.name && name.createdAt.includes(projectDate));
        for (var project in projects) {
          let time = projects[project].timeOfProject[0].split(':')
          seconds += parseInt(time[2])
          minutes += parseInt(time[1])
          hours += parseInt(time[0])
          if (seconds > 59) {
            minutes++;
            seconds = seconds - 59
          }
          if (minutes > 59) {
            hours++
            minutes = minutes - 59
          }
        }

        this.projectSeconds.push(seconds < 10 ? '0' + seconds : seconds);
        this.projectMinutes.push(minutes < 10 ? '0' + minutes : minutes);
        this.projectHours.push(hours < 10 ? '0' + hours : hours);
      }
    }
  },
  methods: {
    startTime: function () {
      this.$refs["start-button"].style.display = 'none';
      this.$refs["pause-button"].style.display = 'inline-flex';
      this.$refs["stop-button"].style.display = 'inline-flex';
      this.$refs["reset-button"].style.display = 'inline-flex';
      let seconds = this.$refs.seconds
      let minutes = this.$refs.minutes
      let hours = this.$refs.hours

      if (this.timer == null) {
        this.timer = setInterval(() => {
          this.seconds++;
          this.seconds = this.seconds < 10 ? '0' + this.seconds : this.seconds;
          seconds.innerHTML = this.seconds;
          if (this.seconds === 60) {
            this.minutes++
            this.minutes = this.minutes < 10 ? '0' + this.minutes : this.minutes;
            this.seconds = '00';
            minutes.innerHTML = this.minutes + ":";
          } else if (this.minutes === 60) {
            this.minutes = '00';
            this.hours++;
            this.hours = this.hours < 10 ? '0' + this.hours : this.hours;
            hours.innerHTML = this.hours + ":"
          }
          this.time = this.hours + ":" + this.minutes + ":" + this.seconds;
        }, 1000)
      }
    },
    pauseTime: function () {
      clearInterval(this.timer);
      this.timer = null;
      this.$refs["start-button"].style.display = 'inline-flex';
    },
    stopTime: function () {
      //pass the time to the input
      this.$refs["time-of-project"].value = this.time
      clearInterval(this.timer);
      this.resetTime()
    },
    resetTime: function () {
      this.seconds = '00';
      this.minutes = '00';
      this.hours = '00';
      this.$refs.seconds.innerHTML = '00';
      this.$refs.minutes.innerHTML = '00:';
      this.$refs.hours.innerHTML = '00:';
    },
    showChildProjects: function (name, date) {
      let firstPartName = name.split(' ');
      let show = document.querySelector(`.child-data-project-` + firstPartName[0]) ? document.querySelector(`.child-data-project-` + firstPartName[0]).childElementCount > 0 : false
      let projects = this.projectsTime.filter(project => project.projectName.name === name && project.createdAt.includes(date));
      if (show === false) {
        for (var project in projects) {
          let element = document.createElement('div');
          element.className = 'child-project'
          element.innerHTML = projects[project].projectName.name + ' ' + projects[project].timeOfProject[0]
          document.querySelector(`.child-data-project-` + firstPartName[0]).appendChild(element)
        }
      } else {
        document.querySelectorAll('.child-project').forEach(project => {
          project.remove();
        })
      }
    },
    delete: function () {
      axios.post('/app/project/delete')
    }
  }
}
</script>