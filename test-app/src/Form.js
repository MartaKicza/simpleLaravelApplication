import React from 'react'
import PropTypes from 'prop-types'
import FormInput from './FormInput'
import Button from './Button';
import './Form.css';

class Form extends React.Component {
  static propTypes = {
    id: PropTypes.string,
    fields: PropTypes.arrayOf(PropTypes.shape({
      id: PropTypes.string.isRequired,
      label: PropTypes.string.isRequired,
      type: PropTypes.string.isRequired,
      dataset: PropTypes.arrayOf(PropTypes.string),
      max: PropTypes.number,
      required: PropTypes.bool,
    })).isRequired,
    addressFields: PropTypes.arrayOf(PropTypes.shape({
      id: PropTypes.string.isRequired,
      label: PropTypes.string.isRequired,
      type: PropTypes.string.isRequired,
      dataset: PropTypes.arrayOf(PropTypes.string),
      max: PropTypes.number,
    })),
    correspondalAddressFields: PropTypes.arrayOf(PropTypes.shape({
      id: PropTypes.string.isRequired,
      label: PropTypes.string.isRequired,
      type: PropTypes.string.isRequired,
      dataset: PropTypes.arrayOf(PropTypes.string),
      max: PropTypes.number,
    })),
    readonly: PropTypes.bool,
    onDataSubmit: PropTypes.func,
    buttonLabel: PropTypes.string,
  }

  static defaultProps = {
    readonly: false,
    onDataSubmit: () => {},
    buttonLabel: "OK",
  }

  constructor(props) {
    super(props);

    this.state = {
      data: (() => {
        let data = this.props.fields.reduce((carry, field) => {
          if (field.defaultValue || field.defaultValue === 0 || field.defaultValue === false) {
            carry[field.id] = field.defaultValue;
          } else {
            carry[field.id] = null;
          }
          return carry;
        }, {});

        if (this.props.addressFields) {
          let address = this.props.addressFields.reduce((carry, field) => {
            if (field.defaultValue || field.defaultValue === 0 || field.defaultValue === false) {
              carry[field.id] = field.defaultValue;
            }
            return carry;
          }, {});
          if (Object.keys(address).length) {
            data.address = address;
          }
        }

        if (this.props.correspondalAddressFields) {
          let address = this.props.correspondalAddressFields.reduce((carry, field) => {
            if (field.defaultValue || field.defaultValue === 0 || field.defaultValue === false) {
              carry[field.id] = field.defaultValue;
            }
            return carry;
          }, {});
          if (Object.keys(address).length) {
            data.correspondal_address = address;
          }
        }

        return data;
      })()
    }
    this._submit = this._submit.bind(this);
    this.handleValueChange = this.handleValueChange.bind(this);
  }

  _submit(e) {
    e.preventDefault();
    var data =  {...this.state.data};
    this.props.onDataSubmit(this._unsetNulls(data));
  }

  _unsetNulls(data) {
    for (let propName in data) { 
      if (data[propName] === null || data[propName] === undefined) {
        delete data[propName];
      }
    }
    return data;
  }

  handleValueChange(value, id, subkey) {
    var data = {...this.state.data};
    if (subkey) {
      if (!data[subkey]) {
        data[subkey] = {};
      }
      data[subkey][id] = value;
    } else {
      data[id] = value;
    }
    this.setState({data: data});
  }

  getData() {
    return this.state.data;
  }

  render() {
    let ca_fields = this.props.correspondalAddressFields;
    if (this.state.data.type_aw && !ca_fields) {
      ca_fields = this.props.addressFields;
    }
    return <form className="Form" id={this.props.id} onSubmit={this._submit}>
      {this.props.fields.map((f) => {
        if (!this.state.data.type_l) {
          if (f.id === 'phone' || f.id === 'education') {
            return null;
          }
        }
        return <FormInput 
          key={f.id} {...f} 
          onValueChange={this.handleValueChange} 
          readonly={this.props.readonly} />
      })}
      {this.state.data.type_aw && <div>
            <h4>Address:</h4>
            {this.props.addressFields.map((f) => {
              return <FormInput 
              key={f.id} {...f} prefix='address_'
              onValueChange={(value, id) => this.handleValueChange(value, id, 'address')} 
              readonly={this.props.readonly} />
            })} 
            <h4>Correspondal address:</h4>
            {ca_fields.map((f) => {
              return <FormInput 
              key={f.id} {...f} prefix='correspondal_address_'
              onValueChange={(value, id) => this.handleValueChange(value, id, 'correspondal_address')} 
              readonly={this.props.readonly} />
            })} 
          </div>}
          <Button 
             type="submit"
             className="Button-success w-100"
          >{this.props.buttonLabel}</Button>
      </form>
  }
}

export default Form;
