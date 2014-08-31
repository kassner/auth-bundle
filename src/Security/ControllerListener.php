<?php

namespace Kassner\AuthBundle\Security;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Util\ClassUtils;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\Common\Annotations\Reader;
use Kassner\AuthBundle\Annotation\Rule;

class ControllerListener
{

    private $annotationReader;
    private $securityContext;

    public function __construct(Reader $annotationReader, SecurityContextInterface $securityContext)
    {
        $this->annotationReader = $annotationReader;
        $this->securityContext = $securityContext;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$this->securityContext->getToken()) {
            return true;
        }

        $controller = $event->getController();
        list($object, $method) = $controller;
        $className = ClassUtils::getRealClass($object);

        $reflectionClass = new \ReflectionClass($className);
        $reflectionMethod = $reflectionClass->getMethod($method);

        $classAnnotations = $this->annotationReader->getClassAnnotations($reflectionClass);
        $methodsAnnotations = $this->annotationReader->getMethodAnnotations($reflectionMethod);

        $allAnnotations = array_merge($classAnnotations, $methodsAnnotations);

        $ruleAnnotations = array_filter($allAnnotations, function($annotation) {
            return $annotation instanceof Rule;
        });

        foreach ($ruleAnnotations as $ruleAnnotation) {
            if (is_array($ruleAnnotation->rules)) {
                $rules = $ruleAnnotation->rules;
            } else {
                $rules = explode(',', $ruleAnnotation->rules);
            }

            foreach ($rules as $rule) {
                $rule = trim($rule);

                if (empty($rule)) {
                    continue;
                }

                if ($this->securityContext->isGranted($rule)) {
                    return true;
                }
            }
        }

        throw new AccessDeniedException('The current user has no permissions on this action.');
    }

}