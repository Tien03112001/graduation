import {Component, OnInit} from '@angular/core';
import {FormBuilder} from '@angular/forms';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ManualDocumentService} from './manual-document.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: 'manual-document.component.html',
  styleUrls: ['manual-document.component.css'],
  providers: [ManualDocumentService]
})
export class ManualDocumentComponent implements OnInit {

  data: any;

  constructor(private service: ManualDocumentService, private title: TitleService, public formBuilder: FormBuilder, private toast: ToasterService) {
    this.title.setTitle('Hướng dẫn sử dụng');
    this.service.get().subscribe((data: any) => {
      this.data = data.content;
    }, (err) => console.log(err));
  }

  ngOnInit() {
  }
}
