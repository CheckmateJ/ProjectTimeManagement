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
    <div class="list-group" ref="list-group">
    </div>
  </div>
</template>
<script>
export default {
  props: ['projectsTime'],
  data() {
    return {
      hours: '00',
      minutes: '00',
      seconds: '0',
      timer: null,
      time: '',
      projectsData: [],
      projectDate: '',
      todayDate: '',
    }
  },
  mounted() {
    this.$refs["pause-button"].style.display = 'none';
    this.$refs["stop-button"].style.display = 'none';
    this.$refs["reset-button"].style.display = 'none';
    this.projectsDisplay();

  },
  beforeMount() {
    for (let i = this.projectsTime.length - 1; i >= 0; i--) {
      let lastCharInDate = this.projectsTime[i].createdAt.indexOf('T');
      let projectDate = this.projectsTime[i].createdAt.slice(0, lastCharInDate);
      if (this.projectsData[this.projectsTime[i].projectName.name] !== projectDate || !this.projectsData[this.projectsTime[i].projectName.name]) {
        this.projectsData[this.projectsTime[i].projectName.name] = projectDate
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
    projectsDisplay: function () {
      // create date as Year-Month-Day
      this.todayDate = new Date().getUTCFullYear() + '-' + (new Date().getUTCMonth() + 1 < 10 ? '0' + (new Date().getUTCMonth() + 1) : new Date().getUTCMonth() + 1) + '-' + new Date().getUTCDate();
      var counts = {};
      this.projectsData = []
      //count the same project
      let lastCharInDate, projectDate
      this.projectsTime.forEach(project => {
        lastCharInDate = project.createdAt.indexOf('T');
        projectDate = project.createdAt.slice(0, lastCharInDate);
        counts[project.projectName.name + ' ' + projectDate] = (counts[project.projectName.name + ' ' + projectDate] || 0) + 1;
      });

      for (let i = this.projectsTime.length - 1; i >= 0; i--) {
        let lastCharInDate = this.projectsTime[i].createdAt.indexOf('T');
        let projectDate = this.projectsTime[i].createdAt.slice(0, lastCharInDate);
        if (this.projectsData[this.projectsTime[i].projectName.name] !== projectDate || !this.projectsData[this.projectsTime[i].projectName.name]) {
          this.projectsData[this.projectsTime[i].projectName.name] = projectDate

          let divList = document.createElement('div');
          let a = document.createElement('a');
          let toggleButton = document.createElement('button')
          let div = document.createElement('div');
          let divChild = document.createElement('div');
          let p = document.createElement('p');
          let projectsTime = this.projectsTime;
          divList.className = 'list-group-item list-group-item-action'
          div.className = 'project-display';
          divChild.className = 'd-inline-flex project-content-box'
          p.className = 'date-display';
          toggleButton.className = 'toggle-button'
          toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-nested" viewBox="0 0 16 16">\n' +
              '              <path fill-rule="evenodd" d="M4.5 11.5A.5.5 0 0 1 5 11h10a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm-2-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm-2-4A.5.5 0 0 1 1 3h10a.5.5 0 0 1 0 1H1a.5.5 0 0 1-.5-.5z"/>\n' +
              '            </svg>'

          p.innerHTML = projectDate === this.todayDate ? 'Today' : projectDate;

          divList.innerHTML = this.projectsTime[i].projectName.name;
          a.innerHTML = counts[this.projectsTime[i].projectName.name + ' ' + projectDate];
          this.$refs['list-group'].appendChild(div);
          div.appendChild(p)
          div.appendChild(divChild)
          divChild.appendChild(divList)
          divList.appendChild(a)
          divList.appendChild(toggleButton)

          toggleButton.addEventListener('click', function () {
            let project = projectsTime.filter(name => name.projectName.name === projectsTime[i].projectName.name && name.createdAt.includes(projectDate));
            let div = document.createElement('div');
            document.querySelector('.list-group').appendChild(div);
          });
        }
      }
    }
  }
}
</script>