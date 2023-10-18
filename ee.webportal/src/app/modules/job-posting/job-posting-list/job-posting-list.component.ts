import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {JobPostingMeta} from '../job-posting.meta';
import {JobPostingService} from '../job-posting.service';
import {JobPostingCreateComponent} from '../job-posting-create/job-posting-create.component';
import {JobPostingEditComponent} from '../job-posting-edit/job-posting-edit.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {JobPostingCvMeta} from '../../job-posting-cv/job-posting-cv.meta';
import {JobPostingCvListComponent} from '../../job-posting-cv/job-posting-cv-list/job-posting-cv-list.component';


const ONE_DAY = 86000000;


@Component({
  selector: 'app-job-posting',
  templateUrl: './job-posting-list.component.html',
  styleUrls: ['./job-posting-list.component.css'],
  providers: [JobPostingService]
})


export class JobPostingListComponent extends AbstractCRUDComponent<JobPostingMeta> {
  slected: any;

  currentTime: any = new Date();


  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý tin tuyển dụng';
  }

  getCreateModalComponent(): any {
    return JobPostingCreateComponent;
  }

  getEditModalComponent(): any {
    return JobPostingEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  initNewModel(): JobPostingMeta {
    return new JobPostingMeta();
  }

  RowSelected(item: any) {
    this.slected = item.id;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      published: new FormControl(null),
      category_id: new FormControl(null),
      tag_id: new FormControl(null),
    });
  }


  initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Tìm kiếm',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Từ khóa',
        class: 'col-md-6'
      },
      FieldForm.createSelect('Tìm kiếm theo xuất bản', 'published', 'Chọn một', [
        {
          value: 1,
          name: 'Đã xuất bản',
        },
        {
          value: 0,
          name: 'Chưa xuất bản',
        }
      ], 'col-md-6')
    ];
  }

  public goToPageNumber() {
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (this.nextPage > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }

  onPublishedChange(item: JobPostingMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Xuất bản';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }

    methodAsync.subscribe((res: JobPostingMeta) => {
      this.list[index].published = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }

  showCV(item: JobPostingCvMeta, index: number) {
    let modalRef = this.modalService.show(JobPostingCvListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-huge'
    });
    let modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new JobPostingCvMeta();
    metaData.job_posting_id = item.id;
    modal.setRelatedModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<JobPostingCvMeta[]>) => {
      if (result.success) {
        this.list[index].job_posting_cvs = result.data;
      }
      sub.unsubscribe();
    });
  }

  checkTimeOneDay(valid_through) {
    const checkOutTime: Date = new Date(valid_through);
    return checkOutTime.getTime() - this.currentTime.getTime() <= ONE_DAY;
  }

  checkTimeTowDay(valid_through) {
    const checkOutTime: Date = new Date(valid_through);
    return checkOutTime.getTime() - this.currentTime.getTime() <= ONE_DAY * 2 && checkOutTime.getTime() - this.currentTime.getTime() > ONE_DAY;
  }

  checkTimeThreeDay(valid_through) {
    return !this.checkTimeOneDay(valid_through) && !this.checkTimeTowDay(valid_through);
  }

  constructor(
    service: JobPostingService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}
