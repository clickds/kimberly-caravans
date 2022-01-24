<template>
  <div class="w-full">
    <div class="w-full bg-white">
      <div class="container mx-auto py-5 px-standard">
        <h1 class="py-3 md:py-6 text-center text-endeavour">
          {{ rangeName }} Videos and Reviews
        </h1>
        <div class="flex flex-row items-center justify-center space-x-5">
          <div class="text-endeavour font-bold text-xl">Viewing</div>
          <select v-model="selectedType" class="p-3 border">
            <option v-for="(type, index) in types" :key="index" :value="type">
              {{ type }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="w-full bg-gallery">
      <div class="py-4 md:py-8 container mx-auto px-standard">
        <div v-if="'Videos' === selectedType">
          <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
            v-if="videos.length > 0"
          >
            <article v-for="(video, index) in videos" :key="index">
              <a :href="video.embedCodeUrl" class="video-lightbox">
                <div
                  class="image-object-fill image-object-center"
                  v-if="video.imageUrl"
                >
                  <img :src="video.imageUrl" />
                </div>
                <div v-else>View Video</div>
              </a>
              <div class="md:px-4 lg:px-8">
                <h4 class="text-endeavour text-xl my-4">
                  {{ video.title }}
                </h4>
                <p class="text-lg">
                  {{ video.excerpt }}
                </p>
              </div>
            </article>
          </div>
          <div class="text-xl" v-else>No videos available</div>
        </div>
        <div v-if="'Reviews' === selectedType">
          <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
            v-if="reviews.length > 0"
          >
            <article
              class="h-full review"
              v-for="(review, index) in reviews"
              :key="index"
            >
              <div class="image-container" v-if="review.imageUrl">
                <img :src="review.imageUrl" />
              </div>

              <div>
                <div class="mt-6 mb-2 font-bold">
                  {{ review.formattedDate }}
                </div>

                <a
                  :href="review.linkUrl"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="mb-2 block text-endeavour underline"
                >
                  {{ review.magazine }}
                </a>

                <h4 class="text-endeavour mb-2">
                  {{ review.title }}
                </h4>

                <p class="mb-2">
                  {{ review.text }}
                </p>

                <a
                  :href="review.linkUrl"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="block text-endeavour underline"
                >
                  Open article
                </a>
              </div>
            </article>
          </div>
          <div class="text-xl" v-else>No reviews available</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    rangeName: {
      type: String,
      required: true,
    },
    videos: {
      type: Array,
      required: true,
    },
    reviews: {
      type: Array,
      required: true,
    },
  },
  data: function () {
    return {
      types: ["Videos", "Reviews"],
      selectedType: "Videos",
    };
  },
};
</script>
