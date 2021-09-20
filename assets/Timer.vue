<template>
  <div class="container">
    <div class="d-inline-flex  justify-content-center timer-box">
      <form name="projec_time" method="post" class="d-inline-flex">
        <div class="me-3"><input id="projec_time_name" name="projec_time[name]" required="required"
                                 class="input-group-text form-control project-name-input "
                                 placeholder="project name"></div>
        <div class="time-hours" ref="hours">00:</div>
        <div class="time-minutes" ref="minutes">00:</div>
        <div class="time-seconds me-2" ref="seconds">00</div>
        <div class="d-inline-flex mb-2">
          <input style="display: none" name="projec_time[timeOfProject]" ref="time-of-project" required="required">
          <div>
            <a class="btn btn-dark me-2" @click="startTime">Start</a>
          </div>
          <div>
            <a class="btn btn-dark me-2" type="submit" @click="pauseTime">Pause</a>
          </div>
          <div>
            <button class="btn btn-dark me-2" name="projec_time[save]" @click="stopTime" type="submit">Stop</button>
          </div>
          <div>
            <a class="btn btn-dark me-2" @click="resetTime">Reset</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      hours: '00',
      minutes: '00',
      seconds: '00',
      timer: null,
      time: ''
    }
  },
  methods: {
    startTime: function () {
      let seconds = this.$refs.seconds
      let minutes = this.$refs.minutes
      let hours = this.$refs.hours

      this.timer = setInterval(() => {
        this.seconds++;
        seconds.innerHTML = this.seconds < 10 ? '0' + this.seconds : this.seconds;
        if (this.seconds == 60) {
          this.minutes++
          this.seconds = '00';
          minutes.innerHTML = this.minutes < 10 ? '0' + this.minutes + ':' : this.minutes + ':';
        } else if (this.minutes == 60) {
          this.minutes = '00';
          this.hours++;
          hours.innerHTML = this.hours < 10 ? '0' + this.hours + ':' : this.hours + ':'
        }
      }, 1000)
    },
    pauseTime: function () {
      clearInterval(this.timer);
    },
    stopTime: function () {
      this.hours = this.hours >= 1 && this.hours < 10 ? '0' + this.hours + ':' : this.hours + ':';
      this.minutes = this.minutes >= 1 && this.minutes < 10 ? '0' + this.minutes + ':' : this.minutes + ':';
      this.seconds = this.seconds >= 1 && this.seconds < 10 ? '0' + this.seconds : this.seconds;
      this.time = this.hours + this.minutes + this.seconds
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