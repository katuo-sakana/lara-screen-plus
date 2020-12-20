<template>
<div class="main">
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container d-flex justify-content-end">
        <button
        class="btn btn-primary p-2"
        v-on:click.stop="commentCreate(positionList)"
        >保存する</button>
      </div>
  </nav>
  
  <!-- {{ commentsdata }} -->
  
  <div class="comment-wrapper">
      <div class="container__contents" id="js-mark">
        <div class="container__contents-inner">
          <template v-for="item in positionList">
            <div v-bind:class="{ 'comment-done' : item.done }" v-if="item.status === true" :key="item.index">
              <div>
                <div>
                  <span class="click-btn">
                    {{ item.index + 1 }}
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
                  <button v-on:click.stop="commentScroll(item.window_y)" class="btn btn-warning p-2">
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
                  top: item.position_y + 'px',
                  left: item.position_x + 'px',
                }"
              >
                {{ item.index + 1 }}
              </div>
  
              <form
                class="update-form"
                v-if="item.form_status === true"
                action
                method="post"
                v-bind:style="{
                  top: item.position_form_y + 'px',
                  left: item.position_form_x + 'px',
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
                    v-on:click.stop="isProcessing(item.index)"
                    >送信</button>
                </div>
              </form>
            </div>
          </template>
        </div>
      </div>
      <!-- id:{{ $route.path }} -->
    </div>
</div>
</template>

<script>
export default {
  props: 
  ['imageurl','endpoint','directory','pageid','commentsdata'],
//   asyncData({ params }) {
//     return { imgSrc: "images/" + params.id + "/00.png" };
//   },
  data() {
    return {
      name: "",
      message: "",
      counter: 1,
      processing: true,
       // 初期値としてからのオブジェクトを入れてないと、updateMessageがうまく動作しないため
      positionList: 
      [
        {}
      ],
    };
  },
  mounted: function () {
    this.$nextTick(function () {
      // ビュー全体がレンダリングされた後にのみ実行されるコード
      if(this.commentsdata !== null) {
        // counter
        this.counter = this.commentsdata.length;
        
        // positionList
        const positionList = this.commentsdata;
        const positionListChange = [];
        for(const positionListItem of positionList) {
          positionListChange.push({
            status: Boolean(positionListItem.status),
            form_status: Boolean(positionListItem.form_status),
            done: Boolean(positionListItem.done),
            is_readonly: Boolean(positionListItem.is_readonly),
            message: positionListItem.message,
            index: positionListItem.index,
            position_x: positionListItem.position_x,
            position_y: positionListItem.position_y,
            window_y: positionListItem.window_y,
            position_form_x: positionListItem.position_form_x,
            position_form_y: positionListItem.position_form_y,
          })
        }
        this.positionList = positionListChange;
      }
    })
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
        form_status: true,
        done: false,
        is_readonly: true,
        message: "",
        index: this.counter,
        position_x: offsetX,
        position_y: offsetY,
        window_y: pageY,
        position_form_x: offsetX,
        position_form_y: offsetY + 50,
      });
      this.counter++;
      this.processing = false;
    },
    isProcessing: function (currentIndex) {
      this.processing = true;
      this.positionList[currentIndex].form_status = false;
    },
    closeMessage: function (currentIndex) {
      this.processing = true;
      this.positionList[currentIndex].status = false;
      this.positionList[currentIndex].form_status = false;
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
    async commentCreate(positionList) {
      for(let positionListItem of positionList){
        // オブジェクトが空ならばループをスキップ
        if (0 === Object.keys(positionListItem).length) {
          continue;
        }
        const request = {
          'status': positionListItem.status,
          'form_status': positionListItem.form_status,
          'done': positionListItem.done,
          'is_readonly': positionListItem.is_readonly,
          'message': positionListItem.message,
          'index': positionListItem.index,
          'position_x': positionListItem.position_x,
          'position_y': positionListItem.position_y,
          'window_y': positionListItem.window_y,
          'position_form_x': positionListItem.position_form_x,
          'position_form_y': positionListItem.position_form_y,
          'page_id': parseInt(this.pageid),
        };
        
        const response = await axios.post(this.endpoint, request);
      }

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