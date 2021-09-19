<template>
  <div class="container">
    <div class="d-inline-flex  justify-content-center timer-box">
      <div class="me-3"><input class="input-group-text form-control project-name-input " name="project name"
                               placeholder="project name"></div>
      <form method="post" class="d-inline-flex">
        <div class="time-hours" ref="hours">00:</div>
        <div class="time-minutes" ref="minutes">00:</div>
        <div class="time-seconds me-2" ref="seconds">00</div>
        <div class="d-inline-flex mb-2">
          <div>
            <button class="btn btn-dark me-2" @click="startTime">Start</button>
          </div>
          <div>
            <button class="btn btn-dark me-2" @click="pauseTime">Pause</button>
          </div>
          <div>
            <button class="btn btn-dark me-2" @click="stopTime" type="submit">Stop</button>
          </div>
          <div>
            <button class="btn btn-dark me-2" @click="resetTime">Reset</button>
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
      timer: null
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
      }, 1)
    },
    pauseTime: function () {
      clearInterval(this.timer);
    },
    stopTime: function () {
      clearInterval(this.timer);
      this.resetTime()
    },
    resetTime: function () {
      this.$refs.seconds.innerHTML = '00';
      this.$refs.minutes.innerHTML = '00:';
      this.$refs.hours.innerHTML = '00:';
    },
  }
}
</script>