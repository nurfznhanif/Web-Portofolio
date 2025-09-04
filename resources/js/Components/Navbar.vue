<template>
  <!-- Modern Navigation with proper spacing -->
  <nav class="fixed top-0 w-full z-50 glassmorphism border-b border-white/10 h-16">
    <div class="container mx-auto px-4 lg:px-6 h-full">
      <div class="flex justify-between items-center h-full">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
          <div
            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0"
          >
            <span class="text-white font-bold text-sm">NH</span>
          </div>
          <span class="text-white font-semibold hidden sm:block text-lg">{{
            profileName
          }}</span>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-8">
          <a
            v-for="item in navItems"
            :key="item.id"
            :href="item.href"
            class="nav-link text-gray-300 hover:text-white transition-all duration-300 text-sm font-medium hover:scale-105 py-2"
            @click="handleNavClick"
          >
            {{ item.label }}
          </a>
        </div>

        <!-- Mobile Menu Button -->
        <button
          @click="toggleMobileMenu"
          class="md:hidden p-2 rounded-lg bg-white/10 backdrop-blur-sm transition-all duration-300 hover:bg-white/20 w-10 h-10 flex items-center justify-center"
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

      <!-- Mobile Menu with proper spacing -->
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
          class="md:hidden border-t border-white/10 bg-gray-900/95 backdrop-blur-md"
        >
          <div class="flex flex-col space-y-1 py-4 px-4">
            <a
              v-for="item in navItems"
              :key="item.id"
              :href="item.href"
              class="text-gray-300 hover:text-white transition-all duration-300 py-3 px-4 rounded-lg hover:bg-white/10 font-medium text-base"
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
      // Calculate offset for fixed navbar
      const navbarHeight = 64; // 4rem = 64px
      const elementPosition = target.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - navbarHeight - 16; // Extra 16px spacing

      window.scrollTo({
        top: offsetPosition,
        behavior: "smooth"
      });

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
/* Navigation link styles with better spacing */
.nav-link {
  position: relative;
  padding: 0.5rem 0;
}

.nav-link::after {
  content: "";
  position: absolute;
  bottom: -2px;
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

/* Glassmorphism effect with proper backdrop */
.glassmorphism {
  background: rgba(15, 15, 35, 0.85);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}

/* Mobile menu improvements */
.mobile-menu-item {
  transform: translateX(-10px);
  opacity: 0;
  animation: slideInLeft 0.3s ease forwards;
}

.mobile-menu-item:nth-child(1) { animation-delay: 0.1s; }
.mobile-menu-item:nth-child(2) { animation-delay: 0.2s; }
.mobile-menu-item:nth-child(3) { animation-delay: 0.3s; }
.mobile-menu-item:nth-child(4) { animation-delay: 0.4s; }
.mobile-menu-item:nth-child(5) { animation-delay: 0.5s; }
.mobile-menu-item:nth-child(6) { animation-delay: 0.6s; }

@keyframes slideInLeft {
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Mobile optimizations */
@media (max-width: 768px) {
  .nav-link {
    font-size: 1rem;
    padding: 0.75rem 0;
  }
}
</style>
