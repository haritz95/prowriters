<template>
   <div class="d-flex p-4">
      <div class="flex-shrink-0">
         <img
            class="avatar float-start"
            :src="comment.user.small_avatar"
            :alt="comment.user.username"
            loading="lazy"
            />              
      </div>
      <div class="flex-grow-1 ms-3 pb-3">
         <div>{{ comment.user.username }}</div>
         <small class="text-muted">{{ __("Posted") }} : {{ localDateTime(comment.created_at) }}</small>
         <div v-html="comment.message"></div>
         <div class="mt-3" v-if="comment.attachments.length > 0">
            <hr>
            {{ __('Attachments') }}
            <ol>
               <li v-for="(attachment, index) in comment.attachments" :key="index">
                  <a :href="attachment.name">
                  {{ attachment.display_name }}
                  </a>
               </li>
            </ol>
         </div>
      </div>
   </div>
</template>
<script>
export default {
    props: ['comment'],
}
</script>