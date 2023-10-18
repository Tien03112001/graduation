import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {StorageUtil} from '../../core/utils';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css'],
  providers: []
})
export class HeaderComponent implements OnInit {

  name: string = '';
  photoUrl: string;
  app_name: string = 'Marketing Portal';

  constructor(private router: Router) {
  }

  ngOnInit() {
    this.name = StorageUtil.get('name');
    this.photoUrl = StorageUtil.get('photoUrl');
  }

  logout() {
    StorageUtil.clear();
    setTimeout(() => {
      this.router.navigateByUrl('login');
    }, 500);
  }
}
