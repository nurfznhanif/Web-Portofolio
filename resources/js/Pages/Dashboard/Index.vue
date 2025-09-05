<!-- Enhanced Dashboard/Index.vue -->
<template>
  <DashboardLayout>
    <div class="p-6 space-y-6">
      <!-- Header dengan Real-time Clock -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-white">Dashboard</h1>
          <p class="text-gray-400 flex items-center mt-1">
            <svg
              class="w-4 h-4 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            {{ currentDateTime }}
          </p>
        </div>
        <div class="flex items-center space-x-3">
          <!-- Refresh Button -->
          <button
            @click="refreshData"
            :disabled="isRefreshing"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center disabled:opacity-50"
          >
            <svg
              class="w-4 h-4 mr-2"
              :class="{ 'animate-spin': isRefreshing }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
              />
            </svg>
            Refresh
          </button>

          <!-- View Portfolio -->
          <a
            :href="route('portfolio.index')"
            target="_blank"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
          >
            <svg
              class="w-4 h-4 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
              />
            </svg>
            View Site
          </a>
        </div>
      </div>

      <!-- Key Metrics Cards dengan Real-time Updates -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Portfolio Projects -->
        <div
          class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-lg p-6 text-white transition-all hover:scale-105"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-blue-200 text-sm font-medium">
                Portfolio Projects
              </p>
              <p class="text-3xl font-bold">{{ stats.portfolios }}</p>
              <div class="flex items-center mt-2">
                <svg
                  class="w-4 h-4 text-green-400 mr-1"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                  />
                </svg>
                <span class="text-green-400 text-sm"
                  >+{{ portfolioGrowth }}% this month</span
                >
              </div>
            </div>
            <div class="p-3 bg-white/10 rounded-full">
              <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                />
              </svg>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="route('admin.portfolio.index')"
              class="text-blue-200 hover:text-white text-sm font-medium flex items-center"
            >
              Manage Projects
              <svg
                class="w-4 h-4 ml-1"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                />
              </svg>
            </Link>
          </div>
        </div>

        <!-- Experience -->
        <div
          class="bg-gradient-to-br from-green-900 to-green-800 rounded-lg p-6 text-white transition-all hover:scale-105"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-green-200 text-sm font-medium">Work Experience</p>
              <p class="text-3xl font-bold">{{ stats.experiences }}</p>
              <div class="flex items-center mt-2">
                <svg
                  class="w-4 h-4 text-blue-400 mr-1"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                <span class="text-green-200 text-sm"
                  >{{ totalExperienceYears }} years total</span
                >
              </div>
            </div>
            <div class="p-3 bg-white/10 rounded-full">
              <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0H8m8 0l-3-3m-5 3l-3-3"
                />
              </svg>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="route('admin.experience.index')"
              class="text-green-200 hover:text-white text-sm font-medium flex items-center"
            >
              Manage Experience
              <svg
                class="w-4 h-4 ml-1"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                />
              </svg>
            </Link>
          </div>
        </div>

        <!-- Skills -->
        <div
          class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-lg p-6 text-white transition-all hover:scale-105"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-purple-200 text-sm font-medium">Total Skills</p>
              <p class="text-3xl font-bold">{{ stats.skills }}</p>
              <div class="flex items-center mt-2">
                <svg
                  class="w-4 h-4 text-yellow-400 mr-1"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                  />
                </svg>
                <span class="text-purple-200 text-sm"
                  >{{ advancedSkills }} advanced</span
                >
              </div>
            </div>
            <div class="p-3 bg-white/10 rounded-full">
              <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                />
              </svg>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="route('admin.skill.index')"
              class="text-purple-200 hover:text-white text-sm font-medium flex items-center"
            >
              Manage Skills
              <svg
                class="w-4 h-4 ml-1"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                />
              </svg>
            </Link>
          </div>
        </div>

        <!-- Messages -->
        <div
          class="bg-gradient-to-br from-yellow-900 to-yellow-800 rounded-lg p-6 text-white transition-all hover:scale-105"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-yellow-200 text-sm font-medium">Unread Messages</p>
              <p class="text-3xl font-bold">{{ unreadMessages }}</p>
              <div class="flex items-center mt-2">
                <div
                  class="w-2 h-2 bg-red-400 rounded-full mr-2 animate-pulse"
                ></div>
                <span class="text-yellow-200 text-sm"
                  >{{ totalMessages }} total</span
                >
              </div>
            </div>
            <div class="p-3 bg-white/10 rounded-full">
              <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4m-4 0H8m-4 0h4m0 0V9a1 1 0 011-1h2a1 1 0 011 1v4M7 7h10"
                />
              </svg>
            </div>
          </div>
          <div class="mt-4">
            <Link
              :href="route('admin.messages.index')"
              class="text-yellow-200 hover:text-white text-sm font-medium flex items-center"
            >
              View Messages
              <svg
                class="w-4 h-4 ml-1"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                />
              </svg>
            </Link>
          </div>
        </div>
      </div>

      <!-- Quick Actions & Recent Activity -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h2 class="text-xl font-bold text-white mb-4 flex items-center">
            <svg
              class="w-5 h-5 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 10V3L4 14h7v7l9-11h-7z"
              />
            </svg>
            Quick Actions
          </h2>
          <div class="grid grid-cols-2 gap-3">
            <Link
              :href="route('admin.portfolio.create')"
              class="flex flex-col items-center p-4 bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors text-center"
            >
              <svg
                class="w-6 h-6 text-white mb-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
              <span class="text-white text-sm font-medium">Add Project</span>
            </Link>

            <Link
              :href="route('admin.experience.create')"
              class="flex flex-col items-center p-4 bg-green-600 hover:bg-green-700 rounded-lg transition-colors text-center"
            >
              <svg
                class="w-6 h-6 text-white mb-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0H8m8 0l-3-3m-5 3l-3-3"
                />
              </svg>
              <span class="text-white text-sm font-medium">Add Experience</span>
            </Link>

            <Link
              :href="route('admin.skill.create')"
              class="flex flex-col items-center p-4 bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors text-center"
            >
              <svg
                class="w-6 h-6 text-white mb-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                />
              </svg>
              <span class="text-white text-sm font-medium">Add Skill</span>
            </Link>

            <button
              @click="clearCache"
              :disabled="clearingCache"
              class="flex flex-col items-center p-4 bg-red-600 hover:bg-red-700 rounded-lg transition-colors text-center disabled:opacity-50"
            >
              <svg
                class="w-6 h-6 text-white mb-2"
                :class="{ 'animate-spin': clearingCache }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                />
              </svg>
              <span class="text-white text-sm font-medium">Clear Cache</span>
            </button>
          </div>
        </div>

        <!-- Recent Projects -->
        <div class="bg-gray-800 rounded-lg p-6">
          <h2 class="text-xl font-bold text-white mb-4 flex items-center">
            <svg
              class="w-5 h-5 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
              />
            </svg>
            Recent Projects
          </h2>
          <div class="space-y-3">
            <div
              v-for="project in stats.recent_portfolios"
              :key="project.id"
              class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors"
            >
              <div
                class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center"
              >
                <span class="text-white text-sm font-bold">{{
                  project.title.charAt(0)
                }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-white font-medium truncate">
                  {{ project.title }}
                </p>
                <p class="text-gray-400 text-sm">{{ project.project_type }}</p>
              </div>
              <div class="flex space-x-2">
                <Link
                  :href="route('admin.portfolio.edit', project.id)"
                  class="text-blue-400 hover:text-blue-300 text-sm"
                >
                  Edit
                </Link>
                <Link
                  :href="route('admin.portfolio.show', project.id)"
                  class="text-green-400 hover:text-green-300 text-sm"
                >
                  View
                </Link>
              </div>
            </div>

            <div
              v-if="stats.recent_portfolios.length === 0"
              class="text-center py-8"
            >
              <svg
                class="mx-auto h-8 w-8 text-gray-400 mb-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
              <p class="text-gray-400 text-sm">No projects yet</p>
              <Link
                :href="route('admin.portfolio.create')"
                class="text-blue-400 hover:text-blue-300 text-sm mt-2 inline-block"
              >
                Create your first project
              </Link>
            </div>
          </div>
        </div>

        <!-- System Status & Analytics Preview -->
        <div class="space-y-6">
          <!-- System Status -->
          <div class="bg-gray-800 rounded-lg p-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
              <svg
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              System Status
            </h2>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-gray-400">Database</span>
                <span class="flex items-center text-green-400">
                  <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                  Healthy
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-400">Cache</span>
                <span class="flex items-center text-green-400">
                  <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                  Active
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-400">Storage</span>
                <span class="flex items-center text-green-400">
                  <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                  Available
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-400">Last Backup</span>
                <span class="text-gray-300 text-sm">{{ lastBackup }}</span>
              </div>
            </div>
          </div>

          <!-- Analytics Preview -->
          <div class="bg-gray-800 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg
                  class="w-5 h-5 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                  />
                </svg>
                Analytics
              </h2>
              <Link
                :href="route('admin.analytics.index')"
                class="text-blue-400 hover:text-blue-300 text-sm font-medium"
              >
                View Details →
              </Link>
            </div>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-gray-400">Total Views</span>
                <span class="text-white font-medium">{{
                  formatNumber(totalViews)
                }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-400">Unique Visitors</span>
                <span class="text-white font-medium">{{
                  formatNumber(uniqueVisitors)
                }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-400">CV Downloads</span>
                <span class="text-white font-medium">{{
                  formatNumber(cvDownloads)
                }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-400">Contact Forms</span>
                <span class="text-white font-medium">{{
                  formatNumber(contactForms)
                }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Activity Feed -->
      <div class="bg-gray-800 rounded-lg p-6">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center">
          <svg
            class="w-5 h-5 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          Recent Activity
        </h2>
        <div class="space-y-4">
          <div
            v-for="activity in recentActivity"
            :key="activity.id"
            class="flex items-center space-x-4 p-4 bg-gray-700 rounded-lg"
          >
            <div
              class="w-10 h-10 rounded-full flex items-center justify-center text-lg"
              :class="getActivityColor(activity.type)"
            >
              {{ getActivityIcon(activity.type) }}
            </div>
            <div class="flex-1">
              <p class="text-white font-medium">{{ activity.title }}</p>
              <p class="text-gray-400 text-sm">{{ activity.description }}</p>
            </div>
            <span class="text-gray-400 text-sm">{{
              formatDate(activity.created_at)
            }}</span>
          </div>

          <div v-if="recentActivity.length === 0" class="text-center py-8">
            <svg
              class="mx-auto h-8 w-8 text-gray-400 mb-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <p class="text-gray-400">No recent activity</p>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps({
  stats: Object,
  analytics: Object,
  recentActivity: Array,
});

// Reactive state
const currentTime = ref(new Date());
const isRefreshing = ref(false);
const clearingCache = ref(false);

// Analytics data
const unreadMessages = ref(0);
const totalMessages = ref(0);
const totalViews = ref(0);
const uniqueVisitors = ref(0);
const cvDownloads = ref(0);
const contactForms = ref(0);

// Computed properties
const currentDateTime = computed(() => {
  return currentTime.value.toLocaleString("id-ID", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
});

const portfolioGrowth = computed(() => {
  // Mock calculation - replace with real data
  return Math.floor(Math.random() * 20) + 5;
});

const totalExperienceYears = computed(() => {
  // Mock calculation - replace with real calculation
  return 3;
});

const advancedSkills = computed(() => {
  // Mock calculation - replace with real data
  return Math.floor(props.stats.skills * 0.4);
});

const lastBackup = computed(() => {
  return "2 hours ago";
});

// Methods
const formatNumber = (num) => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + "M";
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + "K";
  }
  return num?.toString() || "0";
};

const formatDate = (date) => {
  return new Date(date).toLocaleString("id-ID", {
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const getActivityIcon = (type) => {
  const icons = {
    portfolio_created: "✨",
    experience_added: "💼",
    skill_added: "⚡",
    message_received: "💬",
    profile_updated: "👤",
    analytics_updated: "📊",
    cache_cleared: "🗑️",
    backup_created: "💾",
  };
  return icons[type] || "📝";
};

const getActivityColor = (type) => {
  const colors = {
    portfolio_created: "bg-blue-600",
    experience_added: "bg-green-600",
    skill_added: "bg-purple-600",
    message_received: "bg-yellow-600",
    profile_updated: "bg-indigo-600",
    analytics_updated: "bg-pink-600",
    cache_cleared: "bg-red-600",
    backup_created: "bg-gray-600",
  };
  return colors[type] || "bg-gray-600";
};

const updateTime = () => {
  currentTime.value = new Date();
};

const refreshData = async () => {
  isRefreshing.value = true;
  try {
    await router.reload({ only: ["stats", "analytics", "recentActivity"] });
    await loadAnalytics();
  } catch (error) {
    console.error("Failed to refresh data:", error);
  } finally {
    isRefreshing.value = false;
  }
};

const clearCache = async () => {
  clearingCache.value = true;
  try {
    await router.post("/clear-cache");
    // Show success notification
  } catch (error) {
    console.error("Failed to clear cache:", error);
    // Show error notification
  } finally {
    clearingCache.value = false;
  }
};

const loadAnalytics = async () => {
  try {
    const response = await fetch("/admin/api/quick-stats");
    const data = await response.json();

    unreadMessages.value = data.messages || 0;
    totalMessages.value = data.total_messages || 0;
    totalViews.value = data.total_views || 0;
    uniqueVisitors.value = data.unique_visitors || 0;
    cvDownloads.value = data.cv_downloads || 0;
    contactForms.value = data.contact_forms || 0;
  } catch (error) {
    console.error("Failed to load analytics:", error);
  }
};

// Lifecycle
onMounted(() => {
  // Update time every minute
  const timeInterval = setInterval(updateTime, 60000);

  // Load analytics data
  loadAnalytics();

  // Set up periodic analytics refresh (every 5 minutes)
  const analyticsInterval = setInterval(loadAnalytics, 300000);

  // Cleanup function
  onUnmounted(() => {
    clearInterval(timeInterval);
    clearInterval(analyticsInterval);
  });
});
</script>

<style scoped>
.transition-all {
  transition: all 0.3s ease;
}

.hover\:scale-105:hover {
  transform: scale(1.05);
}

@keyframes pulse {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
