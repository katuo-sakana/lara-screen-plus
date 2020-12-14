<template>
    <div class="comment-wrapper">
      <div class="container__contents" id="js-mark">
        <div class="container__contents-inner">
          <template v-for="item in positionList">
            <div v-bind:class="{ 'comment-done' : item.done }" v-if="item.status === true" :key="item.index">
              <div>
                <div>
                  <span class="click-btn">
                    {{ item.index }}
                  </span>
                  <textarea
                    name=""
                    id=""
                    class="update-form-textarea form-control mt-2"
                    placeholder=""
                    v-model="item.message"
                    v-bind:readonly="item.is_readonly"
                    v-on:blur="item.is_readonly = true"
                    cols="30"
                    rows="4"
                  ></textarea>
                </div>
                <div class="d-flex">
                  <button v-on:click.stop="commentScroll(item.windowY)" class="btn btn-warning p-2">
                移動
                  </button>
                  <button v-on:click.stop="commentDelete(item.index)" class="btn btn-danger p-2">
                  削除
                  </button>
                  <button v-on:click.stop="commentDone(item.index)" class="btn btn-success p-2">
                  完了
                  </button>
                  <button v-on:click.stop="commentNotDone(item.index)" class="btn btn-light p-2">
                  未完了
                  </button>
                  <button
                    class="btn btn-info p-2"
                    depressed
                      v-on:click.stop="commentEdit(item.index)"
                  >
                    編集
                  </button>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
      <div class="container__images" id="js-targe" v-on:click="updateMessage">
        <div class="container__images-inner">
          <img :src="imageurl" />
          <template v-for="item in positionList">
            <div v-if="item.status === true" :key="item.index" v-on:click.stop>
              <div
                class="click-btn click-btn--position"
                v-bind:style="{
                  top: item.positionY + 'px',
                  left: item.positionX + 'px',
                }"
              >
                {{ item.index }}
              </div>
  
              <form
                class="update-form"
                v-if="item.formStatus === true"
                action
                method="post"
                v-bind:style="{
                  top: item.positionFormY + 'px',
                  left: item.positionFormX + 'px',
                }"
              >
                <div class="update-form-upper">
                  <div class="pr-3">
                    <button v-on:click.stop="closeMessage(item.index)" type="button" class="close" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                </div>
                <div class="update-form-content">
                  <textarea
                    name=""
                    id=""
                    class="update-form-textarea form-control"
                    placeholder="コメントを入力してください"
                    v-on:click.stop
                    v-model="item.message"
                    cols="30"
                    rows="4"
                  ></textarea>
                </div>
                <div class="update-form-bottom">
                  <button
                    class="btn btn-primary p-2"
                    v-on:click.stop="isProcessing(item.index); commentCreate(item.index)"
                    >送信</button>
                </div>
              </form>
            </div>
          </template>
        </div>
      </div>
      <!-- id:{{ $route.path }} -->
    </div>
</template>

<script>
export default {
  props: 
  ['imageurl','endpoint','directory'],
//   asyncData({ params }) {
//     return { imgSrc: "images/" + params.id + "/00.png" };
//   },
  data() {
    return {
      name: "",
      message: "",
      counter: 1,
      processing: true,
      positionList: [
        {
          status: false,
          formStatus: false,
          done: false,
          is_readonly: true,
          index: 0,
          positionX: 0,
          positionY: 0,
          windowY: 0,
          positionFormX: 0,
          positionFormY: 0,
        },
      ],
    };
  },
  methods: {
    updateMessage: function (e) {
      if (this.processing === false) {
        return;
      }
      let offsetX = e.offsetX; // =>要素左上からのx座標
      let offsetY = e.offsetY; // =>要素左上からのy座標
      let pageX = e.pageX; // =>ウィンドウ左上からのx座標
      let pageY = e.pageY; // =>ウィンドウ左上からのy座標
      // let clientX = e.clientX; // =>ページ左上からのx座標
      // let clientY = e.clientY; // =>ページ左上からのy座標
      this.positionList.push({
        status: true,
        formStatus: true,
        done: false,
        is_readonly: true,
        message: "",
        index: this.counter,
        positionX: offsetX,
        positionY: offsetY,
        windowY: pageY,
        positionFormX: offsetX,
        positionFormY: offsetY + 50,
      });
      this.counter++;
      this.processing = false;
    },
    isProcessing: function (currentIndex) {
      this.processing = true;
      this.positionList[currentIndex].formStatus = false;
    },
    closeMessage: function (currentIndex) {
      this.processing = true;
      this.positionList[currentIndex].status = false;
      this.positionList[currentIndex].formStatus = false;
    },
    commentScroll: function (position) {
      window.scrollTo({
        top: position,
        behavior: 'smooth'
      })
    },
    commentDelete: function (currentIndex) {
      this.processing = true;
      this.positionList[currentIndex].status = false;
    },
    commentDone: function (currentIndex) {
      this.positionList[currentIndex].done = true;
    },
    commentNotDone: function (currentIndex) {
      this.positionList[currentIndex].done = false;
    },
    commentEdit: function (currentIndex) {
      this.positionList[currentIndex].is_readonly = false;
    },
    async commentCreate(currentIndex) {
      this.positionList[currentIndex].is_readonly = false;
      // const articleにそれぞれのindexのstatusやmessageをいれればオッケー
      const request = {
        'status': 'titledesuyo',
        'formStatus': 'content',
        'done': 'content',
        'is_readonly': 'content',
        'message': 'content',
        'index': 'content',
        'positionX': 'content',
        'positionY': 'content',
        'windowY': 'content',
        'positionFormX': 'content',
        'positionFormY': 'content',
        'page_id': 'content',
      };
      
      
      const response = await axios.post(this.endpoint, request);

      // this.isLikedBy = true
      // this.countLikes = response.data.countLikes
      
      
      
      // await axios.post('/' + this.url, article).then(res => {
      //   console.log(res.data.title);
      //   console.log(res.data.content);
      // });
    },
  },
};
</script>

<style scoped>
img {
  max-width: 100%;
  height: auto;
}
.comment-wrapper {
  display: flex;
  justify-content: space-between;
  width: 1400px;
  max-width: 100%;
  margin-right: auto;
  margin-left: auto;
}
.container__contents {
  width: 30%;
}
.container__contents-inner {
  overflow-y: auto;
  position: fixed;
  left: auto;
  height: 90vh;
  /* position: sticky;
  top: 0; */
  /* position: fixed;
  top: 0;
  overflow-y: auto; */
  
  width: 27%;
}
.comment-done {
  opacity: .5;
}
.container__images {
  width: 65%;
  position: relative;
  /* padding-left: 5%;
  padding-right: 5%; */
  /* position: relative; */
}
.container__images-inner {
  /* overflow-y: auto;
  position: fixed;
  left: auto;
  height: 100vh; */
}
.click-btn {
  width: 2em;
  height: 2em;
  border-radius: 50%;
  background-color: blue;
  color: #fff;
  font-weight: bold;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0.2em;
  border: 2px solid #fff;
  box-shadow: 2px 2px 2px #333;
}
.click-btn--position {
  position: absolute;
}
.update-form {
  position: absolute;
  border: 1px solid #aaa;
  border-radius: 0.8em;
  background-color: #fff;
  text-align: right;
  padding-top: 0.5em;
}
.update-form-content-upper {
  text-align: right;
}
.update-form-content {
  /* border-top: 1px solid #aaa; */
  border-bottom: 1px solid #aaa;
  padding: 0.5em;
}
.update-form-textarea {
  padding: 0.3em;
  width: 100%;
}
.update-form-bottom {
  padding: 0.5em;
}
</style>