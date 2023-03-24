<?php //3882b3ab5841e36b0522d6d114dd63da
/** @noinspection all */

namespace Illuminate\Contracts\View {

    /**
     * @method $this extends($view, $params = [])
     * @method $this layout($view, $params = [])
     * @method $this layoutData($data = [])
     * @method $this section($section)
     * @method $this slot($slot)
     */
    class View {}
}

namespace Illuminate\Database\Eloquent {

    /**
     * @method $this onlyTrashed()
     * @method int restore()
     * @method $this withTrashed($withTrashed = true)
     * @method $this withoutTrashed()
     */
    class Builder {}
}

namespace Illuminate\Http {

    /**
     * @method void banner($message)
     * @method void dangerBanner($message)
     */
    class RedirectResponse {}

    /**
     * @method bool hasValidRelativeSignature()
     * @method bool hasValidSignature($absolute = true)
     * @method array validate(array $rules, ...$params)
     * @method void validateWithBag(string $errorBag, array $rules, ...$params)
     */
    class Request {}
}

namespace Illuminate\Routing {

    /**
     * @method $this permission($permissions = [])
     * @method $this role($roles = [])
     */
    class Route {}
}

namespace Illuminate\Support {

    /**
     * @method void downloadExcel(string $fileName, string $writerType = null, $withHeadings = false, array $responseHeaders = [])
     * @method void storeExcel(string $filePath, string $disk = null, string $writerType = null, $withHeadings = false)
     * @method $this trim()
     */
    class Collection {}
}

namespace Illuminate\Support\Facades {

    /**
     * @method void auth($options = [])
     * @method void confirmPassword()
     * @method void emailVerification()
     * @method void resetPassword()
     */
    class Route {}
}

namespace Illuminate\Testing {

    /**
     * @method $this assertDontSeeLivewire($component)
     * @method $this assertSeeLivewire($component)
     */
    class TestResponse {}

    /**
     * @method $this assertDontSeeLivewire($component)
     * @method $this assertSeeLivewire($component)
     */
    class TestView {}
}

namespace Illuminate\View {

    use Livewire\WireDirective;

    /**
     * @method WireDirective wire($name)
     */
    class ComponentAttributeBag {}

    /**
     * @method $this extends($view, $params = [])
     * @method $this layout($view, $params = [])
     * @method $this layoutData($data = [])
     * @method $this section($section)
     * @method $this slot($slot)
     */
    class View {}
}

namespace Yajra\DataTables {

    /**
     * @method $this addTransformer($transformer)
     * @method $this setSerializer($serializer)
     * @method $this setTransformer($transformer)
     */
    class DataTableAbstract {}
}
