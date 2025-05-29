export interface Payment {
  id: string;
  date: string;
  status: "paid" | "failed" | "refunded";
  name: string;
  email: string;
  amount: number;
}
