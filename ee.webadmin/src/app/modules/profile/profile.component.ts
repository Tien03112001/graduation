import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {StorageUtil} from '../../core/utils';
import {ProfileService} from './profile.service';
import {UserMeta} from '../user/user.meta';

@Component({
  selector: 'app-dashboard',
  templateUrl: 'profile.component.html',
  styleUrls: ['profile.component.css'],
  providers: [ProfileService]
})
export class ProfileComponent implements OnInit {

  user: UserMeta;
  form: FormGroup;

  constructor(private service: ProfileService, private title: TitleService, public formBuilder: FormBuilder, private toast: ToasterService) {
    this.title.setTitle('Thông tin cá nhân');
    this.user = new UserMeta();
    this.service.get().subscribe((data: UserMeta) => {
      this.user = data;
      this.form = this.formBuilder.group({
        name: new FormControl(this.user.name, Validators.required),
        oldpassword: new FormControl(null, Validators.minLength(6)),
        password: new FormControl(null, Validators.minLength(6)),
        confirmPassword: new FormControl(null),
      }, {validator: this.matchPassword});
    }, (err) => console.log(err));

  }

  ngOnInit() {
  }

  update() {
    let data = {
      name: this.form.controls['name'].value,
      oldpassword: this.form.controls['oldpassword'].value,
      password: this.form.controls['password'].value,
    };
    this.service.update(data).subscribe(() => {
      this.toast.pop('success', 'Thay đổi thông tin cá nhân', 'Thành công');
      StorageUtil.set('name', data.name);
      this.form.controls['password'].setValue(null);
      this.form.controls['confirmPassword'].setValue(null);
    }, () => {
      this.toast.pop('error', 'Thay đổi thông tin cá nhân', 'Thất bại');
    });
  }

  matchPassword(c: FormGroup) {
    if (c.get('password').value !== c.get('confirmPassword').value) {
      return {matchPassword: true};
    }
    return null;
  }


}
