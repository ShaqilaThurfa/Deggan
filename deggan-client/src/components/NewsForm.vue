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
      <p v-if="imageBase64" class="text-sm text-gray-500 mt-2">Current image:</p>
      <img
        v-if="previewImage"
        :src="previewImage"
        alt="Uploaded image preview"
        class="w-full max-w-xs mt-2"
      />
      <img
        v-else-if="imageBase64 && typeof imageBase64 === 'string'"
        :src="imageBase64"
        alt="Current image"
        class="w-full max-w-xs mt-2"
      />
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
import { useRouter } from 'vue-router'
import { ref, watch } from 'vue'
import Swal from 'sweetalert2'
import axios from 'axios'
import apiConfig from '@/config/api.config'
const imageURL = import.meta.env.VITE_BASE_IMAGE_URL

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
})

const router = useRouter()
const { isCreatePage } = props

const title = ref(props.initialData?.title || '')
const content = ref(props.initialData?.content || '')
const imageBase64 = ref(props.initialData?.imageBase64 || '')

// const handleImageUpload = (event) => {
//   const file = event.target.files[0]
//   if (!file) {
//     Swal.fire({
//       icon: 'error',
//       title: 'Oops...',
//       text: 'Please select an image file!',
//     })
//     return
//   }

//   if (!file.type.startsWith('image/')) {
//     Swal.fire({
//       icon: 'error',
//       title: 'Invalid File',
//       text: 'The selected file must be an image!',
//     })
//     return
//   }

//   const reader = new FileReader()
//   reader.onload = () => {
//     imageBase64.value = reader.result
//   }

//   imageBase64.value = file
// }

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (!file) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Please select an image file!',
    })
    return
  }

  if (!file.type.startsWith('image/')) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid File',
      text: 'The selected file must be an image!',
    })
    return
  }

  // Reset imageBase64 before reading
  imageBase64.value = ''

  const reader = new FileReader()
  reader.onload = () => {
    imageBase64.value = file // Save file for upload
    previewImage.value = reader.result // Save base64 for preview
  }
  reader.readAsDataURL(file)
}

const previewImage = ref('') // Separate variable for base64 preview

const handleCreate = async () => {
  try {
    console.log('Creating news with payload:', {
      title: title.value,
      content: content.value,
      imageBase64: imageBase64.value,
    })

    const formData = new FormData()
    formData.append('title', title.value)
    formData.append('content', content.value)
    formData.append('image', imageBase64.value)

    await axios.post(`${apiConfig.baseURL}/news`, formData, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'multipart/form-data',
      },
    })
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'News created successfully!',
    })

    title.value = ''
    content.value = ''
    imageBase64.value = ''

    router.push('/myposts')
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Failed to create news!',
    })
  }
}

const handleUpdate = async () => {
  try {
    const formData = new FormData()

    formData.append('_method', 'PUT')
    formData.append('title', title.value)
    formData.append('content', content.value)

    if (imageBase64.value instanceof File) {
      formData.append('image', imageBase64.value)
    }

    await axios.post(`${apiConfig.baseURL}/news/${props.initialData.id}`, formData, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'multipart/form-data',
      },
    })

    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: 'News updated successfully!',
    })

    router.push('/myposts')
  } catch (error) {
    console.error('Failed to update news:', error)
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Failed to update news!',
    })
  }
}

const handleSubmit = () => {
  if (!title.value.trim() || !content.value.trim()) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Title and content must not be empty!',
    })
    return
  }

  if (isCreatePage) {
    handleCreate()
  } else {
    handleUpdate()
  }
}

watch(
  () => props.initialData,
  (newData) => {
    if (newData) {
      title.value = newData.title || ''
      content.value = newData.content || ''
      imageBase64.value = newData.image ? `${imageURL}/${newData.image}` : ''
    }
  },
  { immediate: true },
)
</script>
