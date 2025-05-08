# Chip8 Emulator (Symfony Console Command)

![CI](https://github.com/scottdriscoll/chip8/actions/workflows/php.yml/badge.svg)

This is a simple **Chip8 emulator** built as a **Symfony Console Command**.  
It runs entirely inside your terminal — please make sure your terminal window is at least **128x32** characters in size for proper display.

---

## Installation

1. Clone the repository:

```bash
git clone git@github.com:scottdriscoll/chip8.git
cd chip8
```

2. Install dependencies:

```bash
composer install
```

3. (Optional) Ensure your terminal is large enough: minimum **128x32** characters.

---

## Usage

Run the emulator using Symfony's console:

```bash
php bin/console app:chip8 [path] [debug-output-path] [--max-cycles=N]
```

### Arguments

| Argument | Type    | Default                        | Description |
|:---------|:--------|:-------------------------------|:------------|
| `path`   | optional | `tests/fixtures/roms/ibm_logo.ch8` | Path to a Chip8 ROM file. If omitted, a built-in IBM logo ROM is used. |
| `debug-output-path` | optional | — | If provided, debug information will be written to this file during execution. |

### Options

| Option        | Shortcut | Type    | Description |
|:--------------|:---------|:--------|:------------|
| `--max-cycles` | `-m`      | optional | Halt the emulator after executing the given number of instructions. |

---

## Finding ROMs

You are responsible for providing your own Chip8 ROM files.  
Many public domain or homebrew Chip8 programs are available freely online.

Example usage with a custom ROM:

```bash
php bin/console app:chip8 my-roms/pong.ch8
```

Example usage with debug output and max cycles:

```bash
php bin/console app:chip8 my-roms/pong.ch8 debug.txt --max-cycles=10000
```
Running the emulator with the default parameters should display the following output:

![IBM Logo Output](images/ibm.png)

---

## Notes

- **Terminal Size**: Ensure at least **128x32** characters for correct display.
- **Performance**: This emulator is intended for educational and light usage. It may not run ROMs at exact real-time speed.
- **Debugging**: The debug output is mainly intended for developers exploring the emulator's internals.

---

## Commands not yet implemented
- **Sound**: Everything involving sound, (f002, fx3a)
- **timer** game runs too fast still

---

## Future plans, other CHIP variant commands, such as:

- **Scrolling**: 00cn, 00dn (not chip8)
- **fx01**: selecting bit planes (not chip8)
- **hires mode (256x64 terminal size)**: technically we support this already. Would almost certainly need to lower your font size.

Reference [here](https://chip8.gulrak.net/)

---

## License

This project is provided as-is for educational purposes.  
Please respect the licenses of any ROMs you download and use.
