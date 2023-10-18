import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup} from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { AgentPageMeta } from '../agent-page.meta';
import { AgentPageService } from '../agent-page.service';
import { AbstractModalComponent } from '../../../core/crud';
import { FieldForm } from '../../../core/common';
import { ObjectUtil } from '../../../core/utils';
import { TitleService } from '../../../core/services';
import { AgentService } from '../../agent/agent.service';

@Component({
  selector: 'app-agent-page-create',
  templateUrl: './agent-page-create.component.html',
  styleUrls: ['./agent-page-create.component.css'],
  providers: [AgentPageService, AgentService]
})
export class AgentPageCreateComponent extends AbstractModalComponent<AgentPageMeta> {

  public agents = [];

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
    });
  }

  initFieldForm(): FieldForm[] {
    return [];
  }

  loaded(): void {
    this.load();
  }

  formSearchRole: FormGroup = new FormGroup({
    search: new FormControl(),
  })

  constructor(
    service: AgentPageService,
    title: TitleService,
    modal: BsModalRef,
    builder: FormBuilder,
    private agentService: AgentService,
  ) {
    super(service, modal, builder);
  }

  public load() {
    let AgentPages = this.model.existsPages;
    this.agentService.loadAll().subscribe((agents) => {
      this.agents = agents.filter(value => {
        let UserExists = AgentPages.filter(v => v.agent_id == value.id);
        if (UserExists.length == 0) {
          return value.id != this.model.agent_id;
        }
        return false;
      });
    });
  }

  createAgent(r) {
    let item = ObjectUtil.ignoreNullValue(this.model);
    const data = { page_id: item.page_id, agent_id: r.id };
    (<AgentPageService>this.service).attachAgentPage(data).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }


}
