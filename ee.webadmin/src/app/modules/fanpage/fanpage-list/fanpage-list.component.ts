import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {AbstractCRUDComponent, AbstractCRUDModalComponent} from '../../../core/crud';
import {FanpageMeta} from '../fanpage.meta';
import {FieldForm, ModalResult} from '../../../core/common';
import {FanpageService} from '../fanpage.service';
import {FanpageCreateComponent} from '../fanpage-create/fanpage-create.component';
import {FanpageEditComponent} from '../fanpage-edit/fanpage-edit.component';
import {TitleService} from '../../../core/services';
import {ObjectUtil} from '../../../core/utils';
import {AgentPageMeta} from '../../agent-page/agent-page.meta';
import {AgentPageListComponent} from '../../agent-page/agent-page-list/agent-page-list.component';
import {environment} from '../../../../environments/environment';
import {FacebookAdminAccountService} from '../../facebook-admin-account/facebook-admin-account.service';
import {Router} from '@angular/router';

declare var $;
declare var FB;
declare var window;

@Component({
  selector: 'app-fanpage-list',
  templateUrl: './fanpage-list.component.html',
  styleUrls: ['./fanpage-list.component.css'],
  providers: [FanpageService, FacebookAdminAccountService]
})
export class FanpageListComponent extends AbstractCRUDComponent<FanpageMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý fanpage';
  }

  getCreateModalComponent(): any {
    return FanpageCreateComponent;
  }

  getEditModalComponent(): any {
    return FanpageEditComponent;
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

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): FanpageMeta {
    return new FanpageMeta();
  }

  constructor(
    service: FanpageService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private fbService: FacebookAdminAccountService,
  ) {
    super(service, modal, builder,);
    $.getScript(environment.facebook_config.script);
    window.fbAsyncInit = function () {
      FB.init({
        appId: environment.facebook_config.appId,
        cookie: true,
        xfbml: true,
        version: environment.facebook_config.version,
      });
    };
  }

  viewAgent(item: FanpageMeta, i: number) {
    let modalOptions = Object.assign(this.defaultModalOptions(), {'class': 'modal-huge'});
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(AgentPageListComponent, config);
    let modal: AbstractCRUDModalComponent<AgentPageMeta> = <AbstractCRUDModalComponent<AgentPageMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }


  loginFacebook() {
    const fbService = this.fbService;
    FB.login(function (response) {
      if (response.status === 'connected') {
        fbService.addPage({data: JSON.stringify(response)}).subscribe(res => {
          alert('Thêm fanpage thành công');
          setTimeout(function () {
            window.location.reload();
          }, 100);
        }, () => fbService.toastFailedCreated());
      } else {
        fbService.toastError('Lỗi đăng nhập facebook');
      }
    }, {scope: environment.facebook_config.scope});
  }
}
