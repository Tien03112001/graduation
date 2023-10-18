import {UserMeta} from '../user/user.meta';

export class SaleOrderHistoryMeta {
  id: number;
  user_id: string;
  order_id: number;
  from_status: string;
  to_status: string;
  note: string;
  date: string;
  day_of_week: number;
  day_of_month: number;
  month: number;
  year: number;
  hour: number;
  created_at: string;
  updated_at: string;

  user: UserMeta;
}
