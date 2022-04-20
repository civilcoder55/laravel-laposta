<template>
  <div>
    <div class="card-header d-flex align-items-baseline">
      <h3 class="card-title">Edit Post</h3>
      <div class="row d-flex align-items-baseline" style="margin-left: auto">
        <div class="col">
          <div class="dropdown">
            <button
              class="btn btn-primary dropdown-toggle"
              type="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              Accounts
            </button>
            <div
              class="dropdown-menu dropdown-menu-right"
              @click="accountsMenu"
            >
              <div v-for="account in this.userAccounts" :key="account.id">
                <a class="dropdown-item">
                  <div class="custom-control custom-checkbox">
                    <input
                      class="custom-control-input"
                      type="checkbox"
                      v-model="post.accounts"
                      :id="'ach#' + account.id"
                      :value="account.id"
                    />
                    <label
                      :for="'ach#' + account.id"
                      class="custom-control-label"
                      >{{ account.name }} [{{ account.type }}]
                    </label>
                  </div>
                </a>

                <div class="dropdown-divider"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <form :action="`/posts/${post.id}`" method="post">
        <input type="hidden" name="_method" value="PUT" />
        <input type="hidden" name="_token" :value="this.csrfToken" />
        <div class="form-group">
          <label>Post</label>
          <textarea
            class="form-control"
            rows="3"
            v-model="post.message"
            placeholder="Type what you like..."
            name="message"
            required
          ></textarea>
        </div>

        <div class="form-group">
          <label>Schedule</label>
          <div class="input-group date">
            <date-picker
              v-model="post.schedule_date"
              type="datetime"
              format="DD/MM/YYYY hh:mm A"
              value-type="format"
              :disabled-date="(date) => date < new Date(new Date().toLocaleDateString().slice(0, 10))"
              :input-attr="{ name: 'schedule_date' }"
            ></date-picker>
          </div>
        </div>

        <div class="form-check">
          <input
            type="checkbox"
            class="form-check-input"
            v-model="post.draft"
            false-value="0"
            true-value="1"
          />
          <input type="hidden" name="draft" v-model="post.draft" />
          <label>Draft</label>
        </div>
        <input
          v-for="el in post.media"
          :key="el.id"
          type="hidden"
          name="media[]"
          :value="el.id"
        />
        <input
          v-for="el in post.accounts"
          :key="el"
          type="hidden"
          name="accounts[]"
          :value="el"
        />
        <button class="btn btn-primary" type="submit">Schedule</button>
      </form>
    </div>
  </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Bus from "./EventBus";
import moment from "moment";
export default {
  components: { DatePicker },
  props: [
    "userAccounts",
    "selectedAccounts",
    "selectedMedia",
    "editablePost",
    "csrfToken",
  ],
  data() {
    return {
      post: {
        id: this.editablePost.id,
        message: this.editablePost.message,
        accounts: this.selectedAccounts,
        media: this.selectedMedia || [],
        schedule_date: moment(this.editablePost.schedule_date).format(
          "DD/MM/YYYY hh:mm A"
        ),
        draft: this.editablePost.draft || "1",
      },
    };
  },
  methods: {
    accountsMenu: function (e) {
      e.stopPropagation();
    },
  },
  created() {
    Bus.$on("media-selected", ($event) => {
      this.post.media = $event;
    });
  },
};
</script>
