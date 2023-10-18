import {Component, Input, OnInit} from '@angular/core';

@Component({
  selector: 'd-label-active-status',
  templateUrl: './label-active-status.component.html',
  styleUrls: ['./label-active-status.component.css']
})
export class LabelActiveStatusComponent implements OnInit {

  @Input()
  active: boolean;

  @Input()
  labels: string[] = ['Hoạt động', 'Dừng hoạt động'];

  constructor() {
  }

  ngOnInit() {
  }

}
