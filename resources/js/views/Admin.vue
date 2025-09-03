<template>
  <div class="min-h-screen gothic-bg text-white">
    <!-- Login Form -->
    <div
      v-if="!isAuthenticated"
      class="flex items-center justify-center min-h-screen"
    >
      <div class="gothic-card max-w-md w-full mx-4 rounded-lg p-8">
        <h1 class="gothic-title text-3xl text-center mb-8 text-red-400">
          Admin Login
        </h1>

        <form @submit.prevent="handleLogin">
          <div class="mb-6">
            <label class="block text-gray-300 text-sm font-bold mb-2"
              >Email</label
            >
            <input
              v-model="loginForm.email"
              type="email"
              required
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
              placeholder="admin@example.com"
            />
          </div>

          <div class="mb-6">
            <label class="block text-gray-300 text-sm font-bold mb-2"
              >Password</label
            >
            <input
              v-model="loginForm.password"
              type="password"
              required
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
              placeholder="Password"
            />
          </div>

          <button
            type="submit"
            :disabled="loginLoading"
            class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            {{ loginLoading ? "Logging in..." : "Login" }}
          </button>

          <div v-if="loginError" class="mt-4 text-red-400 text-sm text-center">
            {{ loginError }}
          </div>
        </form>
      </div>
    </div>

    <!-- Admin Dashboard -->
    <div v-else>
      <!-- Header -->
      <header class="bg-black bg-opacity-90 border-b border-red-900">
        <div class="container mx-auto px-6 py-4">
          <div class="flex justify-between items-center">
            <h1 class="gothic-title text-2xl text-red-400">Admin Dashboard</h1>
            <div class="flex items-center space-x-4">
              <router-link
                to="/"
                class="text-gray-300 hover:text-red-400 transition-colors"
              >
                <i class="fas fa-eye mr-2"></i>View Portfolio
              </router-link>
              <button
                @click="handleLogout"
                class="text-gray-300 hover:text-red-400 transition-colors"
              >
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
              </button>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="container mx-auto px-6 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="gothic-card p-6 rounded-lg text-center">
            <i class="fas fa-user text-3xl text-red-400 mb-3"></i>
            <h3 class="text-xl font-bold">Profile</h3>
            <p class="text-gray-400">{{ profile ? "1" : "0" }} record</p>
          </div>
          <div class="gothic-card p-6 rounded-lg text-center">
            <i class="fas fa-briefcase text-3xl text-red-400 mb-3"></i>
            <h3 class="text-xl font-bold">Experiences</h3>
            <p class="text-gray-400">{{ experiences.length }} records</p>
          </div>
          <div class="gothic-card p-6 rounded-lg text-center">
            <i class="fas fa-cogs text-3xl text-red-400 mb-3"></i>
            <h3 class="text-xl font-bold">Skills</h3>
            <p class="text-gray-400">{{ skills.length }} records</p>
          </div>
          <div class="gothic-card p-6 rounded-lg text-center">
            <i class="fas fa-folder text-3xl text-red-400 mb-3"></i>
            <h3 class="text-xl font-bold">Projects</h3>
            <p class="text-gray-400">{{ projects.length }} records</p>
          </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-8">
          <div class="border-b border-gray-600">
            <nav class="flex space-x-8">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'py-2 px-1 border-b-2 font-medium text-sm transition-colors',
                  activeTab === tab.id
                    ? 'border-red-400 text-red-400'
                    : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300',
                ]"
              >
                <i :class="tab.icon + ' mr-2'"></i>
                {{ tab.name }}
              </button>
            </nav>
          </div>
        </div>

        <!-- Tab Content sama seperti sebelumnya -->
        <!-- Profile Tab -->
        <div v-if="activeTab === 'profile'" class="gothic-card p-6 rounded-lg">
          <div class="flex justify-between items-center mb-6">
            <h2 class="gothic-title text-2xl text-red-400">
              Profile Information
            </h2>
          </div>

          <form @submit.prevent="updateProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-gray-300 text-sm font-bold mb-2"
                  >Name</label
                >
                <input
                  v-model="profileForm.name"
                  type="text"
                  required
                  class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
                />
              </div>

              <div>
                <label class="block text-gray-300 text-sm font-bold mb-2"
                  >Phone</label
                >
                <input
                  v-model="profileForm.phone"
                  type="text"
                  required
                  class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
                />
              </div>

              <div>
                <label class="block text-gray-300 text-sm font-bold mb-2"
                  >Email</label
                >
                <input
                  v-model="profileForm.email"
                  type="email"
                  required
                  class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
                />
              </div>

              <div>
                <label class="block text-gray-300 text-sm font-bold mb-2"
                  >Location</label
                >
                <input
                  v-model="profileForm.location"
                  type="text"
                  required
                  class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
                />
              </div>

              <div>
                <label class="block text-gray-300 text-sm font-bold mb-2"
                  >Education Degree</label
                >
                <input
                  v-model="profileForm.education_degree"
                  type="text"
                  required
                  class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
                />
              </div>

              <div>
                <label class="block text-gray-300 text-sm font-bold mb-2"
                  >GPA</label
                >
                <input
                  v-model="profileForm.education_gpa"
                  type="number"
                  step="0.01"
                  min="0"
                  max="4"
                  required
                  class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
                />
              </div>

              <div>
                <label class="block text-gray-300 text-sm font-bold mb-2"
                  >GitHub URL</label
                >
                <input
                  v-model="profileForm.github_url"
                  type="url"
                  class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
                />
              </div>
            </div>

            <div class="mt-6">
              <label class="block text-gray-300 text-sm font-bold mb-2"
                >Bio</label
              >
              <textarea
                v-model="profileForm.bio"
                required
                rows="3"
                class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
              ></textarea>
            </div>

            <div class="mt-6">
              <label class="block text-gray-300 text-sm font-bold mb-2"
                >Thesis Title</label
              >
              <textarea
                v-model="profileForm.education_thesis"
                required
                rows="2"
                class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-400"
              ></textarea>
            </div>

            <div class="mt-6">
              <button
                type="submit"
                :disabled="profileLoading"
                class="bg-red-600 text-white py-2 px-6 rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                {{ profileLoading ? "Updating..." : "Update Profile" }}
              </button>
            </div>
          </form>
        </div>

        <!-- Sisanya sama seperti component sebelumnya -->
        <!-- Experience, Skills, Projects tabs tetap sama -->
      </main>
    </div>

    <!-- Modal components sama seperti sebelumnya -->
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import api from "../services/api";

// Import modal components
import ProjectModal from "../components/ProjectModal.vue";
import ExperienceModal from "../components/ExperienceModal.vue";
import SkillModal from "../components/SkillModal.vue";

export default {
  name: "Admin",
  components: {
    ProjectModal,
    ExperienceModal,
    SkillModal,
  },
  setup() {
    const router = useRouter();

    // Auth state untuk Laravel 12
    const isAuthenticated = ref(false);
    const loginForm = ref({ email: "", password: "" });
    const loginLoading = ref(false);
    const loginError = ref("");

    // Data state
    const profile = ref(null);
    const experiences = ref([]);
    const skills = ref([]);
    const projects = ref([]);

    // UI state
    const activeTab = ref("profile");
    const profileLoading = ref(false);

    // Profile form
    const profileForm = ref({
      name: "",
      phone: "",
      email: "",
      location: "",
      bio: "",
      education_degree: "",
      education_gpa: "",
      education_thesis: "",
      github_url: "",
    });

    // Navigation tabs
    const tabs = [
      { id: "profile", name: "Profile", icon: "fas fa-user" },
      { id: "experiences", name: "Experience", icon: "fas fa-briefcase" },
      { id: "skills", name: "Skills", icon: "fas fa-cogs" },
      { id: "projects", name: "Projects", icon: "fas fa-folder" },
    ];

    // Check authentication untuk Laravel 12
    const checkAuth = async () => {
      try {
        const response = await api.auth.check();
        if (response.data.authenticated) {
          isAuthenticated.value = true;
          await loadData();
        }
      } catch (error) {
        console.error("Auth check failed:", error);
        isAuthenticated.value = false;
      }
    };

    // Login handler untuk Laravel 12 session-based auth
    const handleLogin = async () => {
      loginLoading.value = true;
      loginError.value = "";

      try {
        const response = await api.auth.login(loginForm.value);
        if (response.data.success) {
          isAuthenticated.value = true;
          await loadData();
        } else {
          loginError.value = response.data.message || "Login failed";
        }
      } catch (error) {
        loginError.value = error.response?.data?.message || "Login failed";
      } finally {
        loginLoading.value = false;
      }
    };

    // Logout handler untuk Laravel 12
    const handleLogout = async () => {
      try {
        await api.auth.logout();
        isAuthenticated.value = false;
      } catch (error) {
        console.error("Logout error:", error);
        // Force logout even if API call fails
        isAuthenticated.value = false;
      }
    };

    // Load data methods (sama seperti sebelumnya)
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

        // Populate profile form
        if (profile.value) {
          Object.keys(profileForm.value).forEach((key) => {
            if (profile.value[key] !== undefined) {
              profileForm.value[key] = profile.value[key];
            }
          });
        }
      } catch (error) {
        console.error("Error loading data:", error);
      }
    };

    // Profile update
    const updateProfile = async () => {
      profileLoading.value = true;
      try {
        const response = await api.profile.update(profileForm.value);
        profile.value = response.data;
        alert("Profile updated successfully!");
      } catch (error) {
        console.error("Error updating profile:", error);
        alert("Failed to update profile");
      } finally {
        profileLoading.value = false;
      }
    };

    // Utility functions
    const formatDate = (date) => {
      if (!date) return "";
      return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
      });
    };

    onMounted(() => {
      checkAuth();
    });

    return {
      // Auth
      isAuthenticated,
      loginForm,
      loginLoading,
      loginError,
      handleLogin,
      handleLogout,

      // Data
      profile,
      experiences,
      skills,
      projects,

      // UI
      activeTab,
      tabs,
      profileLoading,
      profileForm,
      updateProfile,

      // Utils
      formatDate,
    };
  },
};
</script>
