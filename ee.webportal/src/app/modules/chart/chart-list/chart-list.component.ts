import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ChartMeta} from '../chart.meta';
import {ChartService} from '../chart.service';
import {ChartCreateComponent} from '../chart-create/chart-create.component';
import {ChartEditComponent} from '../chart-edit/chart-edit.component';
import {FieldForm} from '../../../core/common';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {Chart} from 'chart.js';

@Component({
  selector: 'app-chart-list',
  templateUrl: './chart-list.component.html',
  styleUrls: ['./chart-list.component.css'],
  providers: [ChartService]
})
export class ChartListComponent extends AbstractCRUDComponent<ChartMeta> {

  onInit(): void {
    const ctx = document.getElementById('myChart');

    new Chart("myChart", {
      type: 'line',
      data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Chart.js Line Chart'
          }
        }
    }
  })
    ;
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý chart';
  }

  getCreateModalComponent(): any {
    return ChartCreateComponent;
  }

  getEditModalComponent(): any {
    return ChartEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      published: new FormControl(''),
    });
  }

  public initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Tìm kiếm theo tên trang',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Tìm kiếm theo tên trang',
      },
      {
        label: 'Tìm kiếm theo trạng thái',
        type: 'select',
        typeof: 'text',
        formControl: 'published',
        placeHolder: 'Từ khóa',
        data: [
          {
            id: '',
            name: 'Tất cả'
          },
          {
            id: 1,
            name: 'Hoạt động'
          },
          {
            id: 0,
            name: 'Không hoạt động'
          },
        ]
      },
    ];
  }

  initNewModel(): ChartMeta {
    return new ChartMeta();
  }

  constructor(
    service: ChartService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }


  onPublishedChange(item: ChartMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg = 'Hoạt động';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Không hoạt động';
    }

    methodAsync.subscribe((res: ArticleCommentMeta) => {
      this.list[index].enable = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }
}
