<template>
  <div>
    <h2 class="mb-5">Reorder Navigation Items</h2>

    <Errors class="mb-5" :error-bag="errors" />
    <Tree :value="navigationItems" :ondragend="(tree, store) => { this.handleDragStop(tree, store) }"></Tree>

    <form :action="url" method="post">
      <input type="hidden" name="_method" value="PUT" />
      <input type="hidden" name="_token" :value="csrfToken" />
      <input type="hidden" name="navigation_items" :value="navigationItemsJsonString" />
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-5 rounded">Update Order</button>
    </form>
  </div>
</template>

<script>
  import 'he-tree-vue/dist/he-tree-vue.css'
  import {Tree, Draggable} from 'he-tree-vue'
  import Errors from "./Errors";

  export default {
    name: "NavigationItemsTree",
    components: {Errors, Tree: Tree.mixPlugins([Draggable])},
    props: {
      errors: {
        type: Object,
        required: true
      },
      initialNavigationItems: {
        type: Array,
        default: []
      },
      url: {
        type: String,
        default: ""
      },
      csrfToken: {
        type: String,
        default: ""
      }
    },
    data: function() {
      return {
        navigationItems: this.initialNavigationItems,
      };
    },
    methods: {
      handleDragStop(tree) {
        this.navigationItems = tree.treeData;
      },
    },
    computed: {
      navigationItemsJsonString: function() {
        return JSON.stringify(this.navigationItems);
      },
    }
  }
</script>