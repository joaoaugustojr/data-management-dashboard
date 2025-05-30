import type { User } from "./user";

export interface Payment {
  id: string;
  user_id?: string;
  status: "paid" | "failed" | "refunded" | "pending";
  amount: number;
  user: User;
  created_at?: string;
  updated_at?: string;
}
