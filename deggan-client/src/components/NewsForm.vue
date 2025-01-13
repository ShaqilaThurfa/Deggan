<template>
  <form class="mx-auto w-full max-w-md p-8 bg-base-200 rounded-box mt-10">
    <input
      type="text"
      placeholder="Enter title"
      class="input input-bordered w-full max-w-xs mb-4"
      v-model="title"
    />

    <textarea
      class="textarea textarea-bordered w-full mb-4 text-left"
      placeholder="Write content here"
      style="resize: none; min-height: 100px"
      v-model="content"
    ></textarea>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
      <input
        type="file"
        accept="image/*"
        class="file-input file-input-bordered w-full max-w-xs"
        @change="handleImageUpload"
      />
      <p v-if="imageBase64" class="text-sm text-gray-500 mt-2">Image uploaded successfully!</p>
    </div>

    <div class="flex gap-3 justify-end">
      <button class="btn btn-primary" type="button" @click="handleSubmit">
        {{ isCreatePage ? 'Create' : 'Update' }}
      </button>
      <button @click="router.push('/myposts')" class="btn">Back</button>
    </div>
  </form>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { ref } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';
import apiConfig from '@/config/api.config';

const props = defineProps({
  isCreatePage: {
    type: Boolean,
    default: false,
  },
  initialData: {
    type: Object,
    default: () => ({
      title: '',
      content: '',
      imageBase64: '',
    }),
  },
});

const router = useRouter();
const { isCreatePage } = props;

const title = ref(props.initialData?.title || '');
const content = ref(props.initialData?.content || '');
const imageBase64 = ref(props.initialData?.imageBase64 || '');

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (!file) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Please select an image file!',
    });
    return;
  }

  // Pastikan file adalah gambar
  if (!file.type.startsWith('image/')) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid File',
      text: 'The selected file must be an image!',
    });
    return;
  }

  imageBase64.value = file; // Simpan file asli
};


const handleCreate = async() => {
  try {
    console.log('Creating news with payload:', {
      title: title.value,
      content: content.value,
      imageBase64: imageBase64.value,
    });

    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('content', content.value);
    formData.append('image', imageBase64.value);

    await axios.post(`${apiConfig.baseURL}/news`, formData,
    {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'multipart/form-data',
      },
    });
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'News created successfully!',
    });

    title.value = '';
    content.value = '';
    imageBase64.value = '';

    router.push('/myposts');
    
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Failed to create news!',
    });
  }
};

const handleUpdate = () => {
  console.log('Memperbarui berita:', {
    title: title.value,
    content: content.value,
    imageBase64: imageBase64.value,
  });
  Swal.fire({
    icon: 'success',
    title: 'Success',
    text: 'News updated successfully!',
  });
};

const handleSubmit = () => {
  if (!title.value.trim() || !content.value.trim()) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Title and content must not be empty!',
    });
    return;
  }

  if (isCreatePage) {
    handleCreate();
  } else {
    handleUpdate();
  }
};
</script>
