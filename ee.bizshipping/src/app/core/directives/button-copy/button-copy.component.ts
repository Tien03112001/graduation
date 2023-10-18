import {Component, Input, OnInit} from '@angular/core';
import {copyToClipboard} from '../../utils';
import {ToasterService} from 'angular2-toaster';

@Component({
  selector: 'd-button-copy',
  templateUrl: './button-copy.component.html',
  styleUrls: ['./button-copy.component.css']
})
export class ButtonCopyComponent implements OnInit {

  @Input()
  value: any;

  copyToClipboard() {
    copyToClipboard(this.value);
    this.toast.pop('success', 'Copy lưu bộ nhớ đệm', 'Thành công');
  }

  constructor(public toast: ToasterService) {
  }

  ngOnInit() {
  }

}
