import {Component, Input, OnInit} from '@angular/core';

@Component({
  selector: 'd-label-type',
  templateUrl: './label-type.component.html',
  styleUrls: ['./label-type.component.css']
})
export class LabelTypeComponent implements OnInit {

  @Input()
  type: number;

  @Input()
  labels: string[] = ['Pending', 'Doing', 'Success', 'Failed'];

  constructor() {
  }

  ngOnInit() {
  }

}
