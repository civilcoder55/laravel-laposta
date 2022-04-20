<template>
  <div>
    <li class="nav-item dropdown">
      <a
        class="nav-link"
        data-toggle="dropdown"
        href="#"
        @click="clearUnseen()"
      >
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">{{ unseen }}</span>
      </a>
      <div
        class="dropdown-menu dropdown-menu-lg dropdown-menu-right scroll-hide"
      >
        <span class="dropdown-item dropdown-header">Recent Notifications</span>
        <div class="dropdown-divider"></div>
        <div
          v-for="notification in notifications"
          :key="notification.id"
          :id="notification.id"
          :read="notification.read_at ? true : false"
          v-observe-visibility="{
            callback: visibilityChanged,
            once: true,
            throttle: 800,
          }"
        >
          <a class="dropdown-item" :href="notification.data.link">
            <i class="fas mr-2" :class="getClass(notification.data.type)"></i
            >{{ notification.data.message }}
            <span class="float-right text-muted text-sm">{{
              notification.created_at
            }}</span>
          </a>
          <br />
          <div class="dropdown-divider"></div>
        </div>
        <a href="/notifications" class="dropdown-item dropdown-footer"
          >See All Notifications</a
        >
      </div>
    </li>
  </div>
</template>

<script>
export default {
  props: ["notifications", "userId"],
  data() {
    return {
      unseen: "",
    };
  },
  computed: {
    num: function () {
      return parseInt(this.unseen) || 0;
    },
  },
  methods: {
    clearUnseen: function () {
      this.unseen = "";
    },
    getClass: function (type) {
      let classes = {
        login: "fa-exclamation-triangle ",
        post: "fa-envelope",
      };
      return classes[type];
    },
    unreaded: function () {
      let total = this.notifications.filter((n) => !n.read_at).length;
      this.unseen = total == 0 ? "" : total;
    },
    visibilityChanged: function (isVisible, entry) {
      if (isVisible && !entry.target.getAttribute("read")) {
        axios.put(`/notifications/${entry.target.id}`).then((res) => {
          this.unseen = this.num == 0 ? "" : this.unseen - 1;
        });
      }
    },
  },
  created() {
    Echo.private(`users.${this.userId}`).notification((e) => {
      var found = false;
      for (var i = 0; i < this.notifications.length; i++) {
        if (this.notifications[i].id == e.id) {
          found = true;
          break;
        }
      }
      if (!found) {
        this.$toast.open({
          message: e.data.message,
          type: e.data.status,
          duration: 6000,
        });
        this.unseen = this.num + 1;
        this.notifications.unshift(e);
      }
    });
  },
  mounted() {
    this.unreaded();
  },
};
</script>
