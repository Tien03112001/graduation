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

  boxes: any[];
  tables: any[];

  constructor(title: TitleService, private service: DashboardService) {
    title.setTitle('Bảng điều khiển');
  }

  ngOnInit(): void {
    this.service.loadAll().subscribe(val => {
      this.boxes = val['boxes'];
      this.tables = val['tables'];
    });
  }

}
