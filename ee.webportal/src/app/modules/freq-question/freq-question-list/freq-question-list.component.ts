import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {AbstractCRUDComponent} from '../../../core/crud';
import {FreqQuestionMeta} from '../freq-question.meta';
import {FreqQuestionService} from '../freq-question.service';
import {AppPagination} from '../../../core/common';

@Component({
  selector: 'app-freq-question-list',
  templateUrl: './freq-question-list.component.html',
  styleUrls: ['./freq-question-list.component.css'],
  providers: [FreqQuestionService]
})
export class FreqQuestionListComponent extends AbstractCRUDComponent<FreqQuestionMeta> {

  onInit(): void {
    this.loaded();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Câu hỏi thường gặp';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
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

  initNewModel(): FreqQuestionMeta {
    return new FreqQuestionMeta();
  }

  public loaded() {
    this.service.loadAll().subscribe(res => {
      this.list = res;
      }, () => {
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  constructor(
    service: FreqQuestionService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

}
