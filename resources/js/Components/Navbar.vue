<template>
  <nav class="fixed top-0 w-full z-50 glassmorphism border-b border-white/10">
    <div class="container mx-auto px-4 lg:px-6">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
          <div
            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center"
          >
            <span class="text-white font-bold text-sm">NH</span>
          </div>
          <span class="text-white font-semibold hidden sm:block">{{
            profileName
          }}</span>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-8">
          <a
            v-for="item in navItems"
            :key="item.id"
            :href="item.href"
            class="nav-link text-gray-300 hover:text-white transition-all duration-300 text-sm font-medium hover:scale-105"
            @click="handleNavClick"
          >
            {{ item.label }}
          </a>
        </div>

        <!-- Mobile Menu Button -->
        <button
          @click="toggleMobileMenu"
          class="md:hidden p-2 rounded-lg bg-white/10 backdrop-blur-sm transition-all duration-300 hover:bg-white/20"
        >
          <div class="w-5 h-5 flex flex-col justify-center items-center">
            <span
              class="block w-5 h-0.5 bg-white transition-all duration-300"
              :class="{
                'rotate-45 translate-y-0.5': showMobileMenu,
                'mb-1': !showMobileMenu,
              }"
            ></span>
            <span
              class="block w-5 h-0.5 bg-white transition-all duration-300"
              :class="{ 'opacity-0': showMobileMenu, '': !showMobileMenu }"
            ></span>
            <span
              class="block w-5 h-0.5 bg-white transition-all duration-300"
              :class="{
                '-rotate-45 -translate-y-0.5': showMobileMenu,
                'mt-1': !showMobileMenu,
              }"
            ></span>
          </div>
        </button>
      </div>

      <!-- Mobile Menu -->
      <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 transform -translate-y-4"
        enter-to-class="opacity-100 transform translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 transform translate-y-0"
        leave-to-class="opacity-0 transform -translate-y-4"
      >
        <div
          v-if="showMobileMenu"
          class="md:hidden pb-4 border-t border-white/10 mt-2"
        >
          <div class="flex flex-col space-y-2 pt-4">
            <a
              v-for="item in navItems"
              :key="item.id"
              :href="item.href"
              class="text-gray-300 hover:text-white transition-all duration-300 py-3 px-4 rounded-lg hover:bg-white/10 font-medium"
              @click="closeMobileMenu"
            >
              {{ item.label }}
            </a>
          </div>
        </div>
      </transition>
    </div>
  </nav>
</template>

<script setup>
import { ref } from "vue";

// Props
const props = defineProps({
  profileName: {
    type: String,
    default: "Nurfauzan Hanif",
  },
});

// Reactive state
const showMobileMenu = ref(false);

// Navigation items
const navItems = ref([
  { id: "home", label: "Home", href: "#home" },
  { id: "about", label: "About", href: "#about" },
  { id: "portfolio", label: "Portfolio", href: "#portfolio" },
  { id: "experience", label: "Experience", href: "#experience" },
  { id: "skills", label: "Skills", href: "#skills" },
  { id: "contact", label: "Contact", href: "#contact" },
]);

// Methods
const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value;
};

const closeMobileMenu = () => {
  showMobileMenu.value = false;
};

const handleNavClick = (event) => {
  const href = event.target.getAttribute("href");
  if (href && href.startsWith("#")) {
    event.preventDefault();
    const target = document.querySelector(href);
    if (target) {
      target.scrollIntoView({ behavior: "smooth" });
      // Close mobile menu if open
      closeMobileMenu();
    }
  }
};

// Expose methods for parent component
defineExpose({
  closeMobileMenu,
  toggleMobileMenu,
});
</script>

<style scoped>
/* Navigation link styles */
.nav-link {
  position: relative;
}

.nav-link::after {
  content: "";
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(135deg, #3b82f6, #9333ea);
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 100%;
}

/* Active state for navigation */
.nav-link.active {
  color: white;
}

.nav-link.active::after {
  width: 100%;
}

/* Mobile menu animation improvements */
.mobile-menu-item {
  transform: translateX(-10px);
  opacity: 0;
  animation: slideInLeft 0.3s ease forwards;
}

.mobile-menu-item:nth-child(1) {
  animation-delay: 0.1s;
}
.mobile-menu-item:nth-child(2) {
  animation-delay: 0.2s;
}
.mobile-menu-item:nth-child(3) {
  animation-delay: 0.3s;
}
.mobile-menu-item:nth-child(4) {
  animation-delay: 0.4s;
}
.mobile-menu-item:nth-child(5) {
  animation-delay: 0.5s;
}
.mobile-menu-item:nth-child(6) {
  animation-delay: 0.6s;
}

@keyframes slideInLeft {
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Glassmorphism effect */
.glassmorphism {
  background: rgba(15, 15, 35, 0.8);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Hover effects */
button:hover {
  transform: scale(1.05);
}

/* Focus states for accessibility */
button:focus,
a:focus {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Mobile optimizations */
@media (max-width: 768px) {
  .nav-link {
    font-size: 1rem;
    padding: 0.75rem 0;
  }
}
</style>
