<template>
  <div class="components-container">
    <code>Markdown is based on
      <a href="https://github.com/nhnent/tui.editor" target="_blank">tui.editor</a> ，Simply encapsulated in Vue.
      <a target="_blank" href="https://doc.laravue.dev/feature/component/markdown-editor.html">
        Documentation </a>
    </code>

    <div class="editor-container">
      <el-tag class="tag-title">
        Basic:
      </el-tag>
      <markdown-editor v-model="content" height="300px" />
    </div>

    <div class="editor-container">
      <el-tag class="tag-title">
        Markdown Mode:
      </el-tag>
      <markdown-editor ref="markdownEditor" v-model="content" :options="{hideModeSwitch:true,previewStyle:'tab'}" height="200px" />
    </div>

    <div class="editor-container">
      <el-tag class="tag-title">
        Customize Toolbar:
      </el-tag>
      <markdown-editor
        ref="markdownEditor"
        v-model="content"
        :options="{ toolbarItems: ['heading','bold','italic']}"
      />
    </div>

    <div class="editor-container">
      <el-tag class="tag-title">
        I18n:
      </el-tag>
      <el-alert :closable="false" title="You can change the language of the admin system to see the effect" type="success" />
      <markdown-editor v-model="content" :language="language" height="300px" />
    </div>

    <el-button style="margin-top:80px;" type="primary" icon="el-icon-document" @click="getHtml">
      Get HTML
    </el-button>
    <div v-html="html" />
  </div>
</template>

<script>
  import MarkdownEditor from '@/components/MarkdownEditor';

  const content = `
### Markdown Editor
**This is test**

* Laravue
* element
* webpack

`;
export default {
  name: 'MarkdownDemo',
  components: { MarkdownEditor },
  data() {
    return {
      content: content,
      html: '',
      languageTypeList: {
        'en': 'en_US',
        'zh': 'zh_CN',
        'es': 'es_ES',
      },
    };
  },
  computed: {
    language() {
      return this.languageTypeList[this.$store.getters.language];
    },
  },
  methods: {
    getHtml() {
      this.html = this.$refs.markdownEditor.getHtml();
    },
  },
};
</script>

<style scoped>
.editor-container{
  margin-bottom: 30px;
}
.tag-title{
  margin-bottom: 5px;
}
</style>
