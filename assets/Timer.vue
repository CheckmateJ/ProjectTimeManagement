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
        <div class="d-inline-flex mb-2">
          <input style="display: none" name="project[projectReports][1][timeOfProject]" ref="time-of-project"
                 required="required">
          <div>
            <a class="btn btn-dark me-2" @click="startTime" ref="start-button">Start</a>
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
      <div ref="time-project"></div>
    </div>
  </div>
</template>
<script>
export default {
  props: ['projects', 'projectsTime'],
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
    var dateObj = new Date();
    var month = dateObj.getUTCMonth() + 1 < 10 ? '0' + (dateObj.getUTCMonth() + 1) : dateObj.getUTCMonth() + 1; //months from 1-12
    var day = dateObj.getUTCDate();
    var year = dateObj.getUTCFullYear();
    this.todayDate = year + "-" + month + "-" + day;

    for (var project in this.projectsTime) {
      let lastCharInDate = this.projectsTime[project].createdAt.indexOf('T');
      let projectDate = this.projectsTime[project].createdAt.slice(0, lastCharInDate);
      if (this.projectsData[this.projectsTime[project].projectName.name] !== projectDate || !this.projectsData[this.projectsTime[project].projectName.name]) {
        this.projectsData[this.projectsTime[project].projectName.name] = projectDate
        let button = document.createElement('button');
        button.className = 'list-group-item list-group-item-action'
        button.innerHTML = this.projectsTime[project].createdAt + this.projectsTime[project].projectName.name;
        this.$refs['list-group'].appendChild(button);
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
  }
}
</script>