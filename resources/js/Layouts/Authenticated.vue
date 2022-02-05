<template>
  <div>
    <div class="min-h-screen bg-gray-100">
      
      <MenuTop />

      <!-- Page breadcrumb -->
      <nav v-if="$slots.breadcrumb">
        <div class="max-w-7xl bg-white shadow mx-auto py-2 px-3 rounded-b">
          <slot name="breadcrumb" />
        </div>
      </nav>

      <!-- Page Content -->
      <main class="max-w-7xl mx-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
import MenuTop from "@/Layouts/MenuTop.vue";

export default {
  name: "Authenticated",
  components: {
    MenuTop,
  },
};
</script>
