<!-- Copy dari artifact Admin.vue sebelumnya, tapi ganti import api jadi: -->
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
    // ... sisa kode sama seperti sebelumnya
  },
};
</script>
