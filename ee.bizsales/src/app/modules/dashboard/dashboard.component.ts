import {Component, OnInit} from '@angular/core';
import {DashboardService} from './dashboard.service';
import {TitleService} from '../../core/services';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css'],
  providers: [DashboardService]
})
export class DashboardComponent implements OnInit {

  sales: any[];
  sales_value: number;
  orders_count: number;
  orders: any[];
  error_orders_count: any;

  constructor(title: TitleService, private service: DashboardService) {
    title.setTitle('Bảng điều khiển');
  }

  ngOnInit(): void {
    this.service.loadByParams({}).subscribe(res => {
      this.sales = res['sales'];
      this.orders_count = res['orders_count'];
      this.sales_value = res['sales_value'];
      this.orders = res['orders'];
      this.error_orders_count = res['error_orders_count'];
    }, () => {
      this.sales = [];
      this.orders_count = 0;
      this.sales_value = 0;
      this.orders = [];
    });
  }

}
