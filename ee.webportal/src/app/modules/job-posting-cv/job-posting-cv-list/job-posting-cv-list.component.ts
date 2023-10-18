import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {PhotoMeta} from '../../photo-CRUD/photo.meta';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {ModalResult} from '../../../core/common';
import {JobPostingCvMeta} from '../job-posting-cv.meta';
import {JobPostingCvService} from '../job-posting-cv.service';
import {ToasterService} from "angular2-toaster";

@Component({
  selector: 'app-job-posting-cv',
  templateUrl: './job-posting-cv-list.component.html',
  styleUrls: ['./job-posting-cv-list.component.css'],
  providers: [JobPostingCvService]
})
export class JobPostingCvListComponent extends AbstractCRUDModalComponent<JobPostingCvMeta> {

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  getCreateModalComponent(): any {
    return new Error('Method not implemented.');
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  public getEditModalComponent() {
    throw new Error('Method not implemented.');
  }

  getEditModalComponentOptions(): ModalOptions {

    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  initNewModel(): JobPostingCvMeta {
    let newModel: JobPostingCvMeta = new JobPostingCvMeta();
    newModel.job_posting_id = this.relatedModel.job_posting_id;
    return newModel;
  }

  onInit(): void {
  }

  onDestroy(): void {
  }


  loaded(): void {
    this.load();
  }

  constructor(
    service: JobPostingCvService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
    public toast: ToasterService,
  ) {
    super(service, modalRef, modal, builder);
  }

  public load() {
    let param = {
      job_posting_id: this.relatedModel.job_posting_id,
    };
    this.service.loadByParams(param).subscribe((res: JobPostingCvMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  download(item: JobPostingCvMeta) {
    this.toast.pop('success', `Tải xuống file`, 'Thành công');
    window.open(item.cv_file)
  }

  dismiss(): void {
    this.onHidden.emit(new ModalResult<PhotoMeta[]>(this.list));
    this.modal.hide();
  }

  getTitle(): string {
    return "";
  }

}
