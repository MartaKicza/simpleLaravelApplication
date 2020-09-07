import React from 'react'
import PropTypes from 'prop-types'

class FormInput extends React.PureComponent {
  constructor(props) {
    super(props);
    this._handleSimpleChange = this._handleSimpleChange.bind(this);
    this._handleCheckboxChange = this._handleCheckboxChange.bind(this);
    this.handleValueChange = this.handleValueChange.bind(this);
  }

  static propTypes = {
    type: PropTypes.string.isRequired,
    id: PropTypes.string,
    prefix: PropTypes.string,
    dataset: PropTypes.arrayOf(PropTypes.string),
    max: PropTypes.number,
    onValueChange: PropTypes.func,
    defaultValue: PropTypes.any,
    value: PropTypes.any,
    readonly: PropTypes.bool,
  }

  static defaultProps = {
    prefix: ''
  }

  _handleSimpleChange(event) {
    this.props.onValueChange(event.target.value, this.props.id);
  }

  _handleCheckboxChange(event) {
    this.props.onValueChange(event.target.checked, this.props.id);
  }

  handleValueChange(value) {
    this.props.onValueChange(value, this.props.id);
  }

  render() {
    var element;
    var common = {
      id: this.props.prefix + this.props.id,
      defaultValue: this.props.defaultValue,
      value: this.props.value,
      required: this.props.required,
      readOnly: this.props.readonly,
    };
    switch (this.props.type) {
      case 'textarea':
        element = <textarea {...common} onChange={this._handleSimpleChange} />;
          break;
      case 'checkbox':
        element = <input type={this.props.type} 
          readOnly={common.readonly} 
          value="1" 
          required={common.required} 
          defaultChecked={this.props.defaultValue} 
          id={common.id}
          onChange={this._handleCheckboxChange} />;
          break;
      case 'select':
        element = <select {...common} onChange={this._handleSimpleChange}>
            <option>Select</option>
            {this.props.dataset.map((el, id) => <option key={id} value={el} >{el}</option>)}
          </select>;
          break;
      default:
        element = <input type={this.props.type} {...common} onChange={this._handleSimpleChange} />;
    }
    return <div className="Form-row"><label>{this.props.label} {this.props.required ? '*': ''}</label>{element}</div>;
  }
}

export default FormInput
