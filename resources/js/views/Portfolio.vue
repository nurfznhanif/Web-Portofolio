<template>
  <div class="min-h-screen gothic-bg text-white">
    <!-- Navigation -->
    <nav
      class="fixed top-0 w-full z-50 bg-black bg-opacity-90 backdrop-blur-sm border-b border-red-900"
    >
      <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
          <h1 class="gothic-title text-xl text-red-400">Portfolio</h1>
          <div class="hidden md:flex space-x-6">
            <a href="#about" class="hover:text-red-400 transition-colors"
              >About</a
            >
            <a href="#experience" class="hover:text-red-400 transition-colors"
              >Experience</a
            >
            <a href="#skills" class="hover:text-red-400 transition-colors"
              >Skills</a
            >
            <a href="#projects" class="hover:text-red-400 transition-colors"
              >Projects</a
            >
            <a href="#contact" class="hover:text-red-400 transition-colors"
              >Contact</a
            >
          </div>
          <button @click="toggleMobileMenu" class="md:hidden text-red-400">
            <i class="fas fa-bars text-xl"></i>
          </button>
        </div>

        <!-- Mobile Menu -->
        <div
          v-if="showMobileMenu"
          class="md:hidden mt-4 pb-4 border-t border-red-900"
        >
          <div class="flex flex-col space-y-3 pt-4">
            <a
              href="#about"
              @click="toggleMobileMenu"
              class="hover:text-red-400 transition-colors"
              >About</a
            >
            <a
              href="#experience"
              @click="toggleMobileMenu"
              class="hover:text-red-400 transition-colors"
              >Experience</a
            >
            <a
              href="#skills"
              @click="toggleMobileMenu"
              class="hover:text-red-400 transition-colors"
              >Skills</a
            >
            <a
              href="#projects"
              @click="toggleMobileMenu"
              class="hover:text-red-400 transition-colors"
              >Projects</a
            >
            <a
              href="#contact"
              @click="toggleMobileMenu"
              class="hover:text-red-400 transition-colors"
              >Contact</a
            >
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-20 pb-16">
      <div class="container mx-auto px-6 py-20 text-center">
        <div data-aos="fade-up">
          <h1 class="gothic-title text-5xl md:text-7xl mb-6 text-red-400">
            {{ profile?.name || "Loading..." }}
          </h1>
          <p class="text-xl md:text-2xl mb-8 text-gray-300 max-w-3xl mx-auto">
            {{ profile?.bio || "Loading bio..." }}
          </p>
          <div class="flex justify-center space-x-6">
            <a
              v-if="profile?.github_url"
              :href="profile.github_url"
              target="_blank"
              class="gothic-card px-8 py-3 rounded-lg hover:bg-red-900 transition-colors"
            >
              <i class="fab fa-github mr-2"></i> GitHub
            </a>
            <a
              href="#contact"
              class="bg-red-600 px-8 py-3 rounded-lg hover:bg-red-700 transition-colors"
            >
              Get In Touch
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-black bg-opacity-50">
      <div class="container mx-auto px-6">
        <h2
          class="gothic-title text-4xl text-center mb-12 text-red-400"
          data-aos="fade-up"
        >
          About Me
        </h2>
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div data-aos="fade-right">
            <div class="gothic-card p-8 rounded-lg">
              <h3 class="gothic-title text-2xl mb-4 text-red-400">
                Background
              </h3>
              <p class="text-gray-300 mb-6">{{ profile?.bio }}</p>

              <h3 class="gothic-title text-2xl mb-4 text-red-400">Education</h3>
              <div class="space-y-2">
                <p class="text-gray-300">
                  <strong>Degree:</strong> {{ profile?.education_degree }}
                </p>
                <p class="text-gray-300">
                  <strong>GPA:</strong> {{ profile?.education_gpa }}
                </p>
                <p class="text-gray-300">
                  <strong>Thesis:</strong> {{ profile?.education_thesis }}
                </p>
              </div>
            </div>
          </div>

          <div data-aos="fade-left">
            <div class="gothic-card p-8 rounded-lg">
              <h3 class="gothic-title text-2xl mb-4 text-red-400">
                Contact Info
              </h3>
              <div class="space-y-3">
                <div class="flex items-center">
                  <i class="fas fa-envelope text-red-400 mr-3 w-6"></i>
                  <span>{{ profile?.email }}</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-phone text-red-400 mr-3 w-6"></i>
                  <span>{{ profile?.phone }}</span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-map-marker-alt text-red-400 mr-3 w-6"></i>
                  <span>{{ profile?.location }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="py-20">
      <div class="container mx-auto px-6">
        <h2
          class="gothic-title text-4xl text-center mb-12 text-red-400"
          data-aos="fade-up"
        >
          Experience
        </h2>
        <div class="max-w-4xl mx-auto">
          <div
            v-if="experiences.length === 0"
            class="text-center text-gray-400"
          >
            Loading experiences...
          </div>
          <div v-else class="space-y-8">
            <div
              v-for="(exp, index) in experiences"
              :key="exp.id"
              :data-aos="index % 2 === 0 ? 'fade-right' : 'fade-left'"
              class="gothic-card p-6 rounded-lg"
            >
              <div
                class="flex flex-col md:flex-row md:justify-between md:items-start mb-4"
              >
                <div>
                  <h3 class="gothic-title text-xl text-red-400 mb-1">
                    {{ exp.title }}
                  </h3>
                  <p class="text-gray-300 font-semibold">{{ exp.company }}</p>
                </div>
                <div class="text-gray-400 text-sm mt-2 md:mt-0">
                  {{ formatDate(exp.start_date) }} -
                  {{ exp.is_current ? "Present" : formatDate(exp.end_date) }}
                </div>
              </div>
              <p class="text-gray-300">{{ exp.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="py-20 bg-black bg-opacity-50">
      <div class="container mx-auto px-6">
        <h2
          class="gothic-title text-4xl text-center mb-12 text-red-400"
          data-aos="fade-up"
        >
          Skills
        </h2>
        <div class="max-w-6xl mx-auto">
          <div
            v-if="skillsByCategory.length === 0"
            class="text-center text-gray-400"
          >
            Loading skills...
          </div>
          <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
              v-for="(category, index) in skillsByCategory"
              :key="category.name"
              :data-aos="'fade-up'"
              :data-aos-delay="index * 100"
              class="gothic-card p-6 rounded-lg"
            >
              <h3 class="gothic-title text-xl text-red-400 mb-4">
                {{ category.name }}
              </h3>
              <div class="space-y-4">
                <div v-for="skill in category.skills" :key="skill.id">
                  <div class="flex justify-between items-center mb-1">
                    <span class="text-gray-300">{{ skill.name }}</span>
                    <span class="text-sm text-red-400 capitalize">{{
                      skill.level
                    }}</span>
                  </div>
                  <div class="w-full bg-gray-700 rounded-full h-2">
                    <div
                      class="bg-red-600 h-2 rounded-full transition-all duration-1000"
                      :style="{ width: getSkillWidth(skill.level) }"
                    ></div>
                  </div>
                  <p class="text-gray-400 text-sm mt-1">
                    {{ skill.description }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="py-20">
      <div class="container mx-auto px-6">
        <h2
          class="gothic-title text-4xl text-center mb-12 text-red-400"
          data-aos="fade-up"
        >
          Projects
        </h2>

        <!-- Featured Projects -->
        <div v-if="featuredProjects.length > 0" class="mb-16">
          <h3
            class="gothic-title text-2xl text-center mb-8 text-red-300"
            data-aos="fade-up"
          >
            Featured Projects
          </h3>
          <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto">
            <div
              v-for="(project, index) in featuredProjects"
              :key="project.id"
              :data-aos="index % 2 === 0 ? 'fade-right' : 'fade-left'"
              class="gothic-card p-6 rounded-lg"
            >
              <h4 class="gothic-title text-xl text-red-400 mb-3">
                {{ project.title }}
              </h4>
              <p class="text-gray-300 mb-4">{{ project.description }}</p>
              <div class="flex flex-wrap gap-2 mb-4">
                <span
                  v-for="tech in project.technologies"
                  :key="tech"
                  class="px-3 py-1 bg-red-600 text-white rounded-full text-sm"
                >
                  {{ tech }}
                </span>
              </div>
              <div class="flex space-x-4">
                <a
                  v-if="project.github_url"
                  :href="project.github_url"
                  target="_blank"
                  class="text-red-400 hover:text-red-300 transition-colors"
                >
                  <i class="fab fa-github mr-1"></i> Code
                </a>
                <a
                  v-if="project.demo_url"
                  :href="project.demo_url"
                  target="_blank"
                  class="text-red-400 hover:text-red-300 transition-colors"
                >
                  <i class="fas fa-external-link-alt mr-1"></i> Demo
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- All Projects -->
        <div v-if="regularProjects.length > 0">
          <h3
            class="gothic-title text-2xl text-center mb-8 text-red-300"
            data-aos="fade-up"
          >
            Other Projects
          </h3>
          <div
            class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto"
          >
            <div
              v-for="(project, index) in regularProjects"
              :key="project.id"
              :data-aos="'fade-up'"
              :data-aos-delay="index * 100"
              class="gothic-card p-6 rounded-lg"
            >
              <h4 class="gothic-title text-lg text-red-400 mb-3">
                {{ project.title }}
              </h4>
              <p class="text-gray-300 mb-4 text-sm">
                {{ project.description }}
              </p>
              <div class="flex flex-wrap gap-1 mb-4">
                <span
                  v-for="tech in project.technologies.slice(0, 3)"
                  :key="tech"
                  class="px-2 py-1 bg-red-600 text-white rounded-full text-xs"
                >
                  {{ tech }}
                </span>
                <span
                  v-if="project.technologies.length > 3"
                  class="px-2 py-1 bg-gray-600 text-white rounded-full text-xs"
                >
                  +{{ project.technologies.length - 3 }}
                </span>
              </div>
              <div class="flex space-x-3 text-sm">
                <a
                  v-if="project.github_url"
                  :href="project.github_url"
                  target="_blank"
                  class="text-red-400 hover:text-red-300 transition-colors"
                >
                  <i class="fab fa-github mr-1"></i> Code
                </a>
                <a
                  v-if="project.demo_url"
                  :href="project.demo_url"
                  target="_blank"
                  class="text-red-400 hover:text-red-300 transition-colors"
                >
                  <i class="fas fa-external-link-alt mr-1"></i> Demo
                </a>
              </div>
            </div>
          </div>
        </div>

        <div v-if="projects.length === 0" class="text-center text-gray-400">
          Loading projects...
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-black bg-opacity-50">
      <div class="container mx-auto px-6 text-center">
        <h2 class="gothic-title text-4xl mb-8 text-red-400" data-aos="fade-up">
          Get In Touch
        </h2>
        <p
          class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto"
          data-aos="fade-up"
          data-aos-delay="100"
        >
          Interested in working together? I'd love to hear from you.
        </p>
        <div
          class="flex justify-center space-x-6"
          data-aos="fade-up"
          data-aos-delay="200"
        >
          <a
            v-if="profile?.email"
            :href="`mailto:${profile.email}`"
            class="bg-red-600 px-8 py-3 rounded-lg hover:bg-red-700 transition-colors"
          >
            <i class="fas fa-envelope mr-2"></i> Send Email
          </a>
          <a
            v-if="profile?.github_url"
            :href="profile.github_url"
            target="_blank"
            class="gothic-card px-8 py-3 rounded-lg hover:bg-red-900 transition-colors"
          >
            <i class="fab fa-github mr-2"></i> GitHub
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-8 border-t border-red-900">
      <div class="container mx-auto px-6 text-center text-gray-400">
        <p>
          &copy; 2024 {{ profile?.name || "Portfolio" }}. Built with Laravel &
          Vue.js
        </p>
      </div>
    </footer>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import api from "../services/api";
import AOS from "aos";
import "aos/dist/aos.css";

export default {
  name: "Portfolio",
  setup() {
    const profile = ref(null);
    const experiences = ref([]);
    const skills = ref([]);
    const projects = ref([]);
    const showMobileMenu = ref(false);

    const skillsByCategory = computed(() => {
      const categories = {};
      skills.value.forEach((skill) => {
        if (!categories[skill.category]) {
          categories[skill.category] = {
            name: skill.category,
            skills: [],
          };
        }
        categories[skill.category].skills.push(skill);
      });
      return Object.values(categories);
    });

    const featuredProjects = computed(() => {
      return projects.value.filter((project) => project.featured);
    });

    const regularProjects = computed(() => {
      return projects.value.filter((project) => !project.featured);
    });

    const toggleMobileMenu = () => {
      showMobileMenu.value = !showMobileMenu.value;
    };

    const formatDate = (date) => {
      if (!date) return "";
      return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
      });
    };

    const getSkillWidth = (level) => {
      const widths = {
        beginner: "25%",
        intermediate: "50%",
        advanced: "75%",
        expert: "100%",
      };
      return widths[level] || "0%";
    };

    const loadData = async () => {
      try {
        const [profileRes, expRes, skillsRes, projectsRes] = await Promise.all([
          api.profile.get(),
          api.experiences.getAll(),
          api.skills.getAll(),
          api.projects.getAll(),
        ]);

        profile.value = profileRes.data;
        experiences.value = expRes.data;
        skills.value = skillsRes.data;
        projects.value = projectsRes.data;
      } catch (error) {
        console.error("Error loading data:", error);
      }
    };

    onMounted(() => {
      AOS.init({
        duration: 1000,
        once: true,
        offset: 100,
      });
      loadData();
    });

    return {
      profile,
      experiences,
      skills,
      projects,
      showMobileMenu,
      skillsByCategory,
      featuredProjects,
      regularProjects,
      toggleMobileMenu,
      formatDate,
      getSkillWidth,
    };
  },
};
</script>
