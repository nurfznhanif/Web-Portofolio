<template>
  <DashboardLayout>
    <Head :title="portfolio.title" />

    <div class="p-6 max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <div
          class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6"
        >
          <!-- Project Info -->
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-4">
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ portfolio.title }}
              </h1>
              <span
                :class="statusBadgeClass"
                class="px-3 py-1 text-sm font-medium rounded-full"
              >
                {{ statusInfo.icon }} {{ statusInfo.text }}
              </span>
            </div>

            <!-- Meta Information -->
            <div
              class="flex flex-wrap items-center gap-6 text-sm text-gray-600 dark:text-gray-400 mb-4"
            >
              <div class="flex items-center">
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
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                  ></path>
                </svg>
                {{ portfolio.project_type }}
              </div>
              <div class="flex items-center">
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
                    d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"
                  ></path>
                </svg>
                {{ formatDate(portfolio.project_date) }}
              </div>
              <div class="flex items-center">
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
                  ></path>
                </svg>
                {{ projectAge }}
              </div>
              <div class="flex items-center">
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
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  ></path>
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  ></path>
                </svg>
                Order: {{ portfolio.order }}
              </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
              <div
                class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg text-center"
              >
                <div
                  class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                >
                  {{ technologiesArray.length }}
                </div>
                <div class="text-sm text-blue-800 dark:text-blue-200">
                  Technologies
                </div>
              </div>
              <div
                class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg text-center"
              >
                <div
                  class="text-2xl font-bold text-green-600 dark:text-green-400"
                >
                  {{ portfolio.images?.length || 0 }}
                </div>
                <div class="text-sm text-green-800 dark:text-green-200">
                  Images
                </div>
              </div>
              <div
                class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg text-center"
              >
                <div
                  class="text-2xl font-bold text-purple-600 dark:text-purple-400"
                >
                  {{ portfolio.github_url ? "Yes" : "No" }}
                </div>
                <div class="text-sm text-purple-800 dark:text-purple-200">
                  Source Code
                </div>
              </div>
              <div
                class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg text-center"
              >
                <div
                  class="text-2xl font-bold text-orange-600 dark:text-orange-400"
                >
                  {{ portfolio.live_url ? "Yes" : "No" }}
                </div>
                <div class="text-sm text-orange-800 dark:text-orange-200">
                  Live Demo
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex-shrink-0">
            <div class="flex flex-col sm:flex-row gap-3">
              <Link
                :href="`/admin/portfolio/${portfolio.id}/edit`"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
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
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                  ></path>
                </svg>
                Edit Project
              </Link>

              <button
                @click="toggleFeatured"
                :class="featuredButtonClass"
                :disabled="processing"
              >
                <svg
                  class="w-4 h-4 mr-2"
                  :fill="portfolio.is_featured ? 'currentColor' : 'none'"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                  ></path>
                </svg>
                {{
                  portfolio.is_featured ? "Remove Featured" : "Mark Featured"
                }}
              </button>

              <button
                @click="duplicatePortfolio"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                :disabled="processing"
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
                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                  ></path>
                </svg>
                Duplicate
              </button>

              <button
                @click="confirmDelete"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
                :disabled="processing"
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
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                  ></path>
                </svg>
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Description -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
            >
              <svg
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                ></path>
              </svg>
              Project Description
            </h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              {{ portfolio.description }}
            </p>
          </div>

          <!-- Technologies -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
            >
              <svg
                class="w-5 h-5 mr-2 text-purple-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                ></path>
              </svg>
              Technologies Used
            </h2>
            <div class="flex flex-wrap gap-3">
              <span
                v-for="tech in technologiesArray"
                :key="tech"
                class="px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-lg font-medium tech-tag"
              >
                {{ tech }}
              </span>
            </div>
          </div>

          <!-- Challenges & Solutions -->
          <div
            v-if="portfolio.challenges || portfolio.solutions"
            class="grid grid-cols-1 md:grid-cols-2 gap-6"
          >
            <!-- Challenges -->
            <div
              v-if="portfolio.challenges"
              class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
            >
              <h2
                class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
              >
                <svg
                  class="w-5 h-5 mr-2 text-yellow-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.856-.833-2.828 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                  ></path>
                </svg>
                Challenges
              </h2>
              <div
                class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800"
              >
                <p class="text-yellow-800 dark:text-yellow-200">
                  {{ portfolio.challenges }}
                </p>
              </div>
            </div>

            <!-- Solutions -->
            <div
              v-if="portfolio.solutions"
              class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
            >
              <h2
                class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
              >
                <svg
                  class="w-5 h-5 mr-2 text-green-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
                Solutions
              </h2>
              <div
                class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800"
              >
                <p class="text-green-800 dark:text-green-200">
                  {{ portfolio.solutions }}
                </p>
              </div>
            </div>
          </div>

          <!-- Project Images -->
          <div
            v-if="portfolio.images && portfolio.images.length > 0"
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h2
              class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
            >
              <svg
                class="w-5 h-5 mr-2 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                ></path>
              </svg>
              Project Images ({{ portfolio.images.length }})
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
              <div
                v-for="(image, index) in portfolio.images"
                :key="index"
                class="relative group cursor-pointer image-gallery-item"
                @click="openImageModal(index)"
              >
                <img
                  :src="image"
                  :alt="`${portfolio.title} screenshot ${index + 1}`"
                  class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600 group-hover:shadow-lg transition-all duration-300"
                  loading="lazy"
                />
                <div
                  class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center"
                >
                  <svg
                    class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    ></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Project Links -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h3
              class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
            >
              <svg
                class="w-5 h-5 mr-2 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                ></path>
              </svg>
              Project Links
            </h3>

            <div class="space-y-3">
              <div
                v-if="portfolio.github_url"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
              >
                <div class="flex items-center">
                  <svg
                    class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
                    />
                  </svg>
                  <span class="text-sm text-gray-900 dark:text-white"
                    >Source Code</span
                  >
                </div>
                <div class="flex items-center space-x-2">
                  <button
                    @click="copyToClipboard(portfolio.github_url)"
                    class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                    title="Copy URL"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                      ></path>
                    </svg>
                  </button>
                  <a
                    :href="portfolio.github_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                      ></path>
                    </svg>
                  </a>
                </div>
              </div>

              <div
                v-if="portfolio.live_url"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
              >
                <div class="flex items-center">
                  <svg
                    class="w-5 h-5 mr-3 text-gray-600 dark:text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"
                    ></path>
                  </svg>
                  <span class="text-sm text-gray-900 dark:text-white"
                    >Live Demo</span
                  >
                </div>
                <div class="flex items-center space-x-2">
                  <button
                    @click="copyToClipboard(portfolio.live_url)"
                    class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                    title="Copy URL"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                      ></path>
                    </svg>
                  </button>
                  <a
                    :href="portfolio.live_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                      ></path>
                    </svg>
                  </a>
                </div>
              </div>

              <div
                v-if="!portfolio.github_url && !portfolio.live_url"
                class="text-center p-6 text-gray-500 dark:text-gray-400"
              >
                <svg
                  class="w-8 h-8 mx-auto mb-2 opacity-50"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                  ></path>
                </svg>
                <p class="text-sm">No external links available</p>
              </div>
            </div>
          </div>

          <!-- Project Metadata -->
          <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
          >
            <h3
              class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center"
            >
              <svg
                class="w-5 h-5 mr-2 text-gray-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                ></path>
              </svg>
              Project Information
            </h3>

            <div class="space-y-4 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Created:</span>
                <span class="text-gray-900 dark:text-white">{{
                  formatDateTime(portfolio.created_at)
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Updated:</span>
                <span class="text-gray-900 dark:text-white">{{
                  formatDateTime(portfolio.updated_at)
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400"
                  >Project ID:</span
                >
                <span class="text-gray-900 dark:text-white font-mono"
                  >#{{ portfolio.id }}</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400"
                  >Display Order:</span
                >
                <span class="text-gray-900 dark:text-white">{{
                  portfolio.order
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                <span
                  :class="`text-${statusInfo.color}-600 dark:text-${statusInfo.color}-400`"
                >
                  {{ statusInfo.icon }} {{ statusInfo.text }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Image Modal -->
      <div
        v-if="showImageModal && selectedImageIndex !== null"
        class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
        @click="closeImageModal"
      >
        <div class="relative max-w-4xl max-h-full" @click.stop>
          <!-- Close Button -->
          <button
            @click="closeImageModal"
            class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors"
          >
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
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>

          <!-- Image -->
          <img
            :src="portfolio.images[selectedImageIndex]"
            :alt="`${portfolio.title} screenshot ${selectedImageIndex + 1}`"
            class="max-w-full max-h-full object-contain rounded-lg"
          />

          <!-- Navigation Arrows -->
          <button
            v-if="portfolio.images.length > 1"
            @click="prevImage"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2 transition-colors"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              ></path>
            </svg>
          </button>

          <button
            v-if="portfolio.images.length > 1"
            @click="nextImage"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2 transition-colors"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5l7 7-7 7"
              ></path>
            </svg>
          </button>

          <!-- Image Counter -->
          <div
            class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 text-white text-sm"
          >
            {{ selectedImageIndex + 1 }} / {{ portfolio.images.length }}
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Dialog -->
      <ConfirmDialog
        :show="showDeleteDialog"
        title="Delete Project"
        :message="`Are you sure you want to delete '${portfolio.title}'? This action cannot be undone and will permanently remove all project data including images.`"
        confirm-text="Delete Project"
        cancel-text="Cancel"
        type="danger"
        @confirm="deletePortfolio"
        @cancel="showDeleteDialog = false"
      />
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import ConfirmDialog from "@/Components/UI/ConfirmDialog.vue";

// Props
const props = defineProps({
  portfolio: {
    type: Object,
    required: true,
  },
});

// Reactive state
const showDeleteDialog = ref(false);
const processing = ref(false);
const selectedImageIndex = ref(null);
const showImageModal = ref(false);
const copySuccess = ref(false);

// Computed properties
const technologiesArray = computed(() => {
  if (!props.portfolio.technologies) return [];
  return props.portfolio.technologies.split(",").map((tech) => tech.trim());
});

const projectAge = computed(() => {
  const projectDate = new Date(props.portfolio.project_date);
  const today = new Date();
  const diffTime = Math.abs(today - projectDate);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays < 30) return `${diffDays} days ago`;
  if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`;
  return `${Math.floor(diffDays / 365)} years ago`;
});

const statusInfo = computed(() => {
  const status = {
    color: "green",
    text: "Published",
    icon: "✅",
  };

  if (props.portfolio.is_featured) {
    status.color = "blue";
    status.text = "Featured";
    status.icon = "⭐";
  }

  return status;
});

const statusBadgeClass = computed(() => {
  return `bg-${statusInfo.value.color}-100 text-${statusInfo.value.color}-800 dark:bg-${statusInfo.value.color}-900 dark:text-${statusInfo.value.color}-200`;
});

const featuredButtonClass = computed(() => {
  return [
    "inline-flex items-center px-4 py-2 font-medium rounded-lg transition-colors",
    props.portfolio.is_featured
      ? "bg-yellow-100 hover:bg-yellow-200 text-yellow-800 dark:bg-yellow-900 dark:hover:bg-yellow-800 dark:text-yellow-200"
      : "bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300",
    processing.value ? "opacity-50 cursor-not-allowed" : "",
  ];
});

// Methods
const formatDate = (date) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const formatDateTime = (date) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const confirmDelete = () => {
  if (processing.value) return;
  showDeleteDialog.value = true;
};

const deletePortfolio = () => {
  if (processing.value) return;

  processing.value = true;

  router.delete(`/admin/portfolio/${props.portfolio.id}`, {
    onSuccess: () => {
      // Redirect will be handled by controller
    },
    onError: (errors) => {
      console.error("Delete failed:", errors);
      // You could show a toast notification here
    },
    onFinish: () => {
      processing.value = false;
      showDeleteDialog.value = false;
    },
  });
};

const duplicatePortfolio = () => {
  if (processing.value) return;

  processing.value = true;

  router.post(
    `/admin/portfolio/${props.portfolio.id}/duplicate`,
    {},
    {
      onSuccess: () => {
        // Success will redirect to new portfolio
      },
      onError: (errors) => {
        console.error("Duplicate failed:", errors);
      },
      onFinish: () => {
        processing.value = false;
      },
    }
  );
};

const toggleFeatured = () => {
  if (processing.value) return;

  processing.value = true;

  router.patch(
    `/admin/portfolio/${props.portfolio.id}`,
    {
      is_featured: !props.portfolio.is_featured,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        // Portfolio object will be updated by Inertia
      },
      onError: (errors) => {
        console.error("Toggle featured failed:", errors);
      },
      onFinish: () => {
        processing.value = false;
      },
    }
  );
};

const openImageModal = (index) => {
  selectedImageIndex.value = index;
  showImageModal.value = true;
  document.body.style.overflow = "hidden";
};

const closeImageModal = () => {
  showImageModal.value = false;
  selectedImageIndex.value = null;
  document.body.style.overflow = "auto";
};

const nextImage = () => {
  if (selectedImageIndex.value < props.portfolio.images.length - 1) {
    selectedImageIndex.value++;
  } else {
    selectedImageIndex.value = 0;
  }
};

const prevImage = () => {
  if (selectedImageIndex.value > 0) {
    selectedImageIndex.value--;
  } else {
    selectedImageIndex.value = props.portfolio.images.length - 1;
  }
};

const copyToClipboard = async (text) => {
  try {
    await navigator.clipboard.writeText(text);
    copySuccess.value = true;

    // Reset success state after 2 seconds
    setTimeout(() => {
      copySuccess.value = false;
    }, 2000);

    // You could show a toast notification here
    console.log("Copied to clipboard:", text);
  } catch (err) {
    console.error("Failed to copy:", err);
    // Fallback for older browsers
    const textArea = document.createElement("textarea");
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    try {
      document.execCommand("copy");
      copySuccess.value = true;
      setTimeout(() => (copySuccess.value = false), 2000);
    } catch (fallbackErr) {
      console.error("Fallback copy failed:", fallbackErr);
    }
    document.body.removeChild(textArea);
  }
};

// Keyboard navigation for image modal
const handleKeydown = (event) => {
  if (!showImageModal.value) return;

  switch (event.key) {
    case "Escape":
      closeImageModal();
      break;
    case "ArrowLeft":
      prevImage();
      break;
    case "ArrowRight":
      nextImage();
      break;
  }
};

// Add keyboard event listener
document.addEventListener("keydown", handleKeydown);

// Cleanup
const cleanup = () => {
  document.removeEventListener("keydown", handleKeydown);
  document.body.style.overflow = "auto";
};

// Vue lifecycle
import { onUnmounted } from "vue";
onUnmounted(cleanup);
</script>

<style scoped>
/* Image modal animations */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Smooth hover transitions */
.transition-all {
  transition: all 0.3s ease;
}

/* Technology tags styling */
.tech-tag {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.tech-tag:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Image gallery enhancements */
.image-gallery-item {
  position: relative;
  overflow: hidden;
  border-radius: 0.5rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-gallery-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Loading state for buttons */
.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}

/* Copy button feedback */
.copy-success {
  color: #10b981 !important;
}

/* Focus styles for accessibility */
button:focus,
a:focus {
  outline: 2px solid rgba(99, 102, 241, 0.5);
  outline-offset: 2px;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: rgba(156, 163, 175, 0.5);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(156, 163, 175, 0.7);
}

/* Mobile optimizations */
@media (max-width: 640px) {
  .grid-cols-2 {
    grid-template-columns: 1fr;
  }

  .flex-col.sm\:flex-row {
    flex-direction: column;
  }

  .space-x-3 > * + * {
    margin-left: 0;
    margin-top: 0.75rem;
  }

  .text-3xl {
    font-size: 1.875rem;
    line-height: 2.25rem;
  }

  .text-xl {
    font-size: 1.125rem;
    line-height: 1.75rem;
  }
}

/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }

  .print-full-width {
    width: 100% !important;
    max-width: none !important;
  }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  .bg-blue-100 {
    background-color: #dbeafe !important;
    border: 1px solid #3b82f6 !important;
  }

  .text-blue-800 {
    color: #1e40af !important;
  }
}

/* Dark mode image modal */
@media (prefers-color-scheme: dark) {
  .image-modal {
    background-color: rgba(0, 0, 0, 0.95);
  }
}

/* Animation for status badge */
.status-badge {
  animation: fadeInScale 0.5s ease-out;
}

@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: scale(0.8);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Loading spinner */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Responsive design helpers */
.responsive-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}

/* Image lazy loading placeholder */
.image-placeholder {
  background: linear-gradient(90deg, #f0f0f0 25%, transparent 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
}

@keyframes loading {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

/* Smooth transitions for all interactive elements */
.interactive {
  transition: all 0.2s ease-in-out;
}

.interactive:hover {
  transform: translateY(-1px);
}

.interactive:active {
  transform: translateY(0);
}
</style>
