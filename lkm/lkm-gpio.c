#include <linux/kernel.h>
#include <linux/init.h>
#include <linux/module.h>
#include <linux/gpio.h>
#include <linux/interrupt.h>

MODULE_LICENSE("GPL");
MODULE_AUTHOR("Deyan Spasov");
MODULE_DESCRIPTION("A Button & LED driver with a button press interrupt handler");
MODULE_VERSION("0.3");

static unsigned int gpioLED = 16; 
static unsigned int gpioButton = 21;   
static unsigned int irqNumber = 0;    
static int ledState = 0;

// Decleration for the IRQ handler function, implemented below
static irq_handler_t  gpio_irq_handler(unsigned int irq, void *device_id, struct pt_regs *regs);

static int __init gpioLKM_init(void) {
   int result = 0;
   
   if (!gpio_is_valid(gpioLED) || !gpio_is_valid(gpioButton)) {
      printk(KERN_INFO "Invalid GPIO, pleasy try again with a different pin number.\n");
      return -ENODEV;
   }

   ledState = 1;
   gpio_request(gpioLED, "sysfs");
   // Set the gpio to be in output mode and on
   gpio_direction_output(gpioLED, ledState); 
   // Causes gpio to appear in /sys/class/gpio and the bool argument prevents the direction from being changed
   gpio_export(gpioLED, false);

   gpio_request(gpioButton, "sysfs");      
   gpio_direction_input(gpioButton);
   // Debounce the button
   gpio_set_debounce(gpioButton, 1000);
   gpio_export(gpioButton, false);

   // mapping the GPIO and IRQ numbers as they are different
   irqNumber = gpio_to_irq(gpioButton);
   
   // registering an interrupt event for the button press
   result = request_irq(irqNumber,
                        (irq_handler_t) gpio_irq_handler,
                        IRQF_TRIGGER_RISING,
                        "gpio_handler",
                        NULL);
   return result;
}

static void __exit gpioLKM_exit(void) {
   gpio_set_value(gpioLED, 0);
   gpio_unexport(gpioLED);
   free_irq(irqNumber, NULL);              
   gpio_unexport(gpioButton);               
   gpio_free(gpioLED);                     
   gpio_free(gpioButton);
}

static irq_handler_t gpio_irq_handler(unsigned int irq, void *device_id, struct pt_regs *regs) {
   ledState = !ledState;                        
   gpio_set_value(gpioLED, ledState);

   // the IRQ has been handled correctly
   return (irq_handler_t) IRQ_HANDLED;
}

module_init(gpioLKM_init);
module_exit(gpioLKM_exit);