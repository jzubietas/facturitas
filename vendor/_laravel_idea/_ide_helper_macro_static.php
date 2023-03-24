<?php //a7e1b59ad9ef3a1953bcbd7863201c4a
/** @noinspection all */

namespace Illuminate\Contracts\View {

    /**
     * @method static $this extends($view, $params = [])
     * @method static $this layout($view, $params = [])
     * @method static $this layoutData($data = [])
     * @method static $this section($section)
     * @method static $this slot($slot)
     */
    class View {}
}

namespace Illuminate\Database\Eloquent {

    /**
     * @method static $this onlyTrashed()
     * @method static int restore()
     * @method static $this withTrashed($withTrashed = true)
     * @method static $this withoutTrashed()
     */
    class Builder {}
}

namespace Illuminate\Http {

    /**
     * @method static void banner($message)
     * @method static void dangerBanner($message)
     */
    class RedirectResponse {}

    /**
     * @method static bool hasValidRelativeSignature()
     * @method static bool hasValidSignature($absolute = true)
     * @method static array validate(array $rules, ...$params)
     * @method static void validateWithBag(string $errorBag, array $rules, ...$params)
     */
    class Request {}
}

namespace Illuminate\Routing {

    /**
     * @method static $this permission($permissions = [])
     * @method static $this role($roles = [])
     */
    class Route {}
}

namespace Illuminate\Support {

    /**
     * @method static void downloadExcel(string $fileName, string $writerType = null, $withHeadings = false, array $responseHeaders = [])
     * @method static void storeExcel(string $filePath, string $disk = null, string $writerType = null, $withHeadings = false)
     * @method static $this trim()
     */
    class Collection {}
}

namespace Illuminate\Support\Facades {

    /**
     * @method static void auth($options = [])
     * @method static void confirmPassword()
     * @method static void emailVerification()
     * @method static void resetPassword()
     */
    class Route {}
}

namespace Illuminate\Testing {

    /**
     * @method static $this assertDontSeeLivewire($component)
     * @method static $this assertSeeLivewire($component)
     */
    class TestResponse {}

    /**
     * @method static $this assertDontSeeLivewire($component)
     * @method static $this assertSeeLivewire($component)
     */
    class TestView {}
}

namespace Illuminate\View {

    use Livewire\WireDirective;

    /**
     * @method static WireDirective wire($name)
     */
    class ComponentAttributeBag {}

    /**
     * @method static $this extends($view, $params = [])
     * @method static $this layout($view, $params = [])
     * @method static $this layoutData($data = [])
     * @method static $this section($section)
     * @method static $this slot($slot)
     */
    class View {}
}

namespace Yajra\DataTables {

    /**
     * @method static $this addTransformer($transformer)
     * @method static $this setSerializer($serializer)
     * @method static $this setTransformer($transformer)
     */
    class DataTableAbstract {}
}
