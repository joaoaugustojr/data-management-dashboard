import { getUsers } from "~/services/user";
import type { User } from "~/types/user";

export const useUserStore = defineStore("users", () => {
  const users = ref<User[]>([]);
  const loading = ref(false);

  const loadUsers = async (name?: string) => {
    loading.value = true;

    try {
      const response = await getUsers(name);
      users.value = response.data.value.data as User[];
    } catch (error: any) {
      throw new Error("Failed to load users", error.message);
    } finally {
      loading.value = false;
    }
  };

  return { users, loading, loadUsers };
});
