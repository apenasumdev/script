import moment from 'moment'
import {IUser} from "@/helpers/Interfaces"

export default class User implements IUser{
    public constructor(private attrs){}
    //Getters
    get id(){return this.attrs.id}
    get fname(){return this.attrs.fname}
    get lname(){return this.attrs.lname}
    get full_name(){return `${this.fname} ${this.lname}`}
    get email(){return this.attrs.email}
    get token(){return this.attrs.token}
    get role(){return this.attrs.role}
    get admin(){return this.attrs.role === 'admin'}
    get created_at(){return moment(this.attrs.created_at).fromNow()}
    get updated_at(){return moment(this.attrs.updated_at).fromNow() || ''}
    getAttributes(){return this.attrs}
}