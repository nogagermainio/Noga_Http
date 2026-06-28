<?php
namespace Src\Routes\Definition;
enum DefinitionType: string {
    case Closure = 'closure';
    case Classes = 'class';
    case ClassMethod = 'class_method';
    case Function = 'function';
    case Invokable = 'invokable';
    case Null = '';
}