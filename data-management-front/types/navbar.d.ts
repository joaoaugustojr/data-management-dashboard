export interface NavItem {
  name: string;
  icon: string;
  to?: string;
  external?: boolean;
  action?: () => void;
}
