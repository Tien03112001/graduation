import {Component} from '@angular/core';
import {BsModalRef} from 'ngx-bootstrap';
import {TitleService} from '../../../core/services';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {AbstractModalComponent} from '../../../core/crud';
import {WidgetService} from '../../widget/widget.service';
import {WidgetMeta} from '../../widget/widget.meta';

@Component({
  selector: 'app-block-view',
  templateUrl: './block-view.component.html',
  styleUrls: ['./block-view.component.css'],
  providers: [WidgetService]
})
export class BlockViewComponent extends AbstractModalComponent<WidgetMeta> {
  view_html: any;

  onInit(): void {
  }

  onDestroy(): void {
  }

  loaded(): void {
    this.view_html = this.model.view_html;
  }

  constructor(
    service: WidgetService,
    modal: BsModalRef,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  dismiss() {
    this.modal.hide();
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      html: new FormControl(null, Validators.required),
      js: new FormControl(null),
      css: new FormControl(null),
      view_html: new FormControl(null),
    });
  }
}
