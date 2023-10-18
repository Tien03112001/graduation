import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {EmbedCodeMeta} from '../embed-code.meta';
import {EmbedCodeService} from '../embed-code.service';
import {EmbedCodeCreateComponent} from '../embed-code-create/embed-code-create.component';
import {EmbedCodeEditComponent} from '../embed-code-edit/embed-code-edit.component';

@Component({
  selector: 'app-embed-code',
  templateUrl: './embed-code-list.component.html',
  styleUrls: ['./embed-code-list.component.css'],
  providers: [EmbedCodeService]
})
export class EmbedCodeListComponent extends AbstractCRUDComponent<EmbedCodeMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Code nhúng';
  }

  getCreateModalComponent(): any {
    return EmbedCodeCreateComponent;
  }

  getEditModalComponent(): any {
    return EmbedCodeEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): EmbedCodeMeta {
    return new EmbedCodeMeta();
  }

  constructor(
    service: EmbedCodeService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
