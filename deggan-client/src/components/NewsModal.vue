<template>
  <dialog ref="modal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl gap-3">
      <h3 class="font-bold text-lg pb-4">Create New Post</h3>
      
      
      <input 
        type="text" 
        placeholder="Enter title" 
        class="input input-bordered w-full max-w-xs mb-4" 
      />
      
      <textarea 
        class="textarea textarea-bordered w-full mb-4 text-left" 
        placeholder="Write content here"
        style="resize: none; padding: 10px; min-height: 100px;">
      </textarea>

      
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Upload Image
        </label>
        <input 
          type="file" 
          accept="image/*" 
          class="file-input file-input-bordered w-full max-w-xs" 
        />
      </div>

   
      <div class="modal-action">
        <button @click="close" class="btn">Close</button>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { ref } from 'vue';

const modal = ref(null);

const open = () => {
  modal.value?.showModal();
};

const close = () => {
  modal.value?.close();
};


defineExpose({
  open,
  close,
});
</script>
