import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { ModalResult } from '../../../core/common';
import { AbstractCRUDModalComponent, AbstractModalComponent } from '../../../core/crud';
import { TitleService } from '../../../core/services';
import { ObjectUtil } from '../../../core/utils';
import { AgentPageCreateComponent } from '../agent-page-create/agent-page-create.component';
import { AgentPageEditComponent } from '../agent-page-edit/agent-page-edit.component';
import { AgentPageMeta } from '../agent-page.meta';
import { AgentPageService } from '../agent-page.service';

@Component({
  selector: 'app-agent-page-list',
  templateUrl: './agent-page-list.component.html',
  styleUrls: ['./agent-page-list.component.css'],
  providers: [AgentPageService]
})
export class AgentPageListComponent extends AbstractCRUDModalComponent<AgentPageMeta> {
  formBuilder: any;

  getTitle(): string {
    return 'Quản lý tư vấn viên';
  }

  getCreateModalComponent(): any {
    return AgentPageCreateComponent;
  }

  getEditModalComponent(): any {
    return AgentPageEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { 'class': 'modal-lg' };
  }

  getEditModalComponentOptions(): ModalOptions {
    return { 'class': 'modal-lg' };
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      page_id: new FormControl(null, Validators.required)
    });
  }

  initNewModel(): AgentPageMeta {
    let agent: AgentPageMeta = new AgentPageMeta();
    agent.page_id = this.relatedModel.id;
    agent.existsPages = this.list;
    return agent;
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loaded(): void {
    this.load();
  }

  constructor(
    service: AgentPageService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, modalService, builder);
  }

  public load() {
    let param = {
      page_id: this.relatedModel.id,
    };
    this.service.loadByParams(param).subscribe((res: AgentPageMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  createAgent() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<AgentPageMeta> = <AbstractModalComponent<AgentPageMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<AgentPageMeta>) => {
      this.load();
    });
  }

  remove(item: AgentPageMeta, index: number) {
    (<AgentPageService>this.service).detachAgentPage({
      agent_id: item.agent_id,
      page_id: this.relatedModel.id,
    }).subscribe(res => {
      this.list.splice(index, 1);
      this.service.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());
  }
}
